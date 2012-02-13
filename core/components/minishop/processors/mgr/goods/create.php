<?php
/**
 * miniShop
 *
 * Copyright 2010 by Shaun McCormick <shaun+minishop@modx.com>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
/**
 * Create an Goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Проверка обязательных полей
if (empty($_POST['pagetitle'])) {
	$modx->error->addField('pagetitle',$modx->lexicon('ms.required_field'));
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$tmp = explode(',', $modx->getOption('minishop.goods_tpl', '', 1));
$_POST['template'] = $tmp[0];

$response = $modx->runProcessor('resource/create', $_POST);
if ($response->isError()) {
    return $modx->error->failure($response->getMessage());
}

$id = $response->response['object']['id'];

if ($res = $modx->getObject('modResource', $id)) {
	$wids = array();
	// Если обновляем информацию на всех складах сразу - достаем их id
	$tmp = $modx->getCollection('ModWarehouse');
	foreach ($tmp as $v) {
		$permission = $v->get('permission');
		if (!empty($permission) && !$modx->hasPermission($permission)) {
			continue;
		}
		else {
			$wids[] = $v->get('id');
		}
	}

	foreach ($wids as $wid) {
		$res2 = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
		$res2->set('article', $_REQUEST['article']);
		$res2->set('price', $_REQUEST['price']);
		$res2->set('img', $_REQUEST['img']);
		$res2->set('remains', $_REQUEST['article']);
		$res2->save();
	}

}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);