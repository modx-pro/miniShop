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
 * Create an OrderedGoods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Проверка обязательных полей
if (empty($_POST['gid'])) {
	$modx->error->addField('gid',$modx->lexicon('ms.required_field'));
}
if (empty($_POST['num'])) {
	$modx->error->addField('num',$modx->lexicon('ms.required_field'));
}
else if ($_POST['num'] < 0) {
	$_POST['num'] = 0;
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}
if (!empty($_POST['data'])) {
	if (!json_decode($_POST['data'], true) && $_POST['data'] != '[]') {
		 return $modx->error->failure($modx->lexicon('ms.orderedgoods.err_data'));
	}
}

$miniShop = new miniShop($modx);

if ($res = $modx->getObject('modResource', $_POST['gid'])) {
	$price = !empty($_POST['price']) ? $_POST['price'] : $miniShop->getPrice($_POST['gid']);
	$weight = !empty($_POST['weight']) ? $_POST['weight'] : $miniShop->getWeight($_POST['gid']);
	$sum = $_POST['num'] * $price;
	
	if ($goods = $modx->getObject('ModOrderedGoods', $_POST['id'])) {
		$goods->fromArray(array(
			'gid' => $_POST['gid']
			,'oid' => $_POST['oid']
			,'num' => $_POST['num']
			,'price' => $price
			,'weight' => $weight
			,'sum' => $sum
			,'data' => !empty($_POST['data']) ? $_POST['data'] : json_encode(array())
		));
		$goods->save();
	}
	else {
		return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
	}
	if ($order = $modx->getObject('ModOrders', $_POST['oid'])) {
		$order->updateSum();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);