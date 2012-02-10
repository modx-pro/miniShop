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
 * Update an Order from grid
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);
$oid = $tmp['oid'];
$num = $tmp['num'];
$price = $tmp['price'];
$sum = $num * $price;

if (empty($tmp['id'])) {
	return $modx->error->failure($modx->lexicon('ms.orders.goods_err_save'));
}

$item = $modx->getObject('ModOrderedGoods', $tmp['id']);
if (!$item)  {
	return $modx->error->failure($modx->lexicon('ms.orders.goods_err_save'));
}


$item->set('num', $num);
$item->set('sum', $sum);
$item->set('price', $price);

if ($item->save() == false) {
	return $modx->error->failure($modx->lexicon('ms.orders.goods_err_save'));
}

if ($res = $modx->getObject('ModOrders', $oid)) {
	//$res->updateSum();
	if ($goods = $modx->getCollection('ModOrderedGoods', array('oid' => $res->get('id')))) {
		$sum = 0;
		foreach ($goods as $v) {
			$sum += $v->get('sum');
		}
		$res->set('sum', $sum);
		$res->save();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.orders.item_err_save'));
}

/* output */
$itemArray = $item->toArray('',true);
//$itemArray = $modx->getObject('ModOrders', $tmp['id'])->toArray();
return $modx->error->success('',$itemArray);