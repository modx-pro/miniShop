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
 * Update an Goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $modx->getOption('id', $_REQUEST, 0);
$status = $modx->getOption('status', $_REQUEST, 1);
$wid = $modx->getOption('wid', $_REQUEST, 0);

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}
if (empty($wid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.wh_err_ns'));
}

if ($res = $modx->getObject('modResource', $id)) {
	$wids = array();
	// Если обновляем информацию на всех складах сразу - достаем их id
	if ($_REQUEST['duplicate']) {
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
	}
	else {
		$wids[] = $wid;
	}
	
	foreach ($wids as $wid) {
		if (!$res2 = $modx->getObject('ModGoods', array('wid' => $wid, 'gid' => $id))) {
			$res2 = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
		}
		$res2->set('article', $_REQUEST['article']);
		$res2->set('price', $_REQUEST['price']);
		$res2->set('img', $_REQUEST['img']);
		$res2->set('remains', $_REQUEST['remains']);
		$res2->save();
		miniShop::Log('status', $id, 'change', $oldstatus, $status);
	}

	// Обновляем ресурс в таблице MODX
	$res->set('pagetitle', $_REQUEST['pagetitle']);
	$res->set('longtitle', $_REQUEST['longtitle']);
	$res->set('content', $_REQUEST['content']);
	$res->set('parent', $_REQUEST['parent']);
	
	if ($res->save() == false) {
		return $modx->error->failure($modx->lexicon('ms.goods.err_save'));
	}
	
	// Защита от дублирования основной и добавочной категории товара
	if ($tmp = $modx->getObject('ModCategories', array('gid' => $id, 'cid' => $_REQUEST['parent']))) {
		$tmp->remove();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

return $modx->error->success('', $res);