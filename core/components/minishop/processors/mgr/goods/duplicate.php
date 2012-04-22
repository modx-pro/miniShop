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
 * Duplicate an Goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Проверка обязательных полей
if (empty($_POST['id'])) {
	$modx->error->failure('pagetitle',$modx->lexicon('ms.required_field'));
}

$response = $modx->runProcessor('resource/duplicate', array('id' => $_POST['id'], 'name' => 'Copy of res #'.$_POST['id']));
if ($response->isError()) {
    return $modx->error->failure($response->getMessage());
}

$id_old = $_POST['id'];
$id_new = $response->response['object']['id'];

if ($res_old = $modx->getObject('ModGoods', array('gid' => $id_old, 'wid' => $_SESSION['minishop']['warehouse']))) {
	$tmp = $res_old->toArray();
	unset($tmp['id']);
	$tmp['gid'] = $id_new;

	$res_new = $modx->newObject('ModGoods');
	$res_new->fromArray($tmp);
	if (!$res_new->save()) {
		return $modx->error->failure('ms.orders.goods_err_save');
	}
	
	$res = $modx->getIterator('ModCategories', array('gid' => $id_old));
	foreach ($res as $v) {
		$cid = $v->get('cid');
		$tmp = $modx->newObject('ModCategories', array('gid' => $id_new, 'cid' => $cid));
		$tmp->save();
	}

	$res = $modx->getIterator('ModGallery', array('gid' => $id_old));
	foreach ($res as $v) {
		$tmp = $modx->newObject('ModGallery');
		$tmp->fromArray($v->toArray());
		$tmp->set('id', 0);
		$tmp->set('gid', $id_new);
		$tmp->save();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res_new);
