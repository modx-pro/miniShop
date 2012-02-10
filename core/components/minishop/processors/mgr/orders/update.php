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
 * Update an Order
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $modx->getOption('id', $_REQUEST, 0);
$status = $modx->getOption('status', $_REQUEST, 1);
$comment = $modx->getOption('comment', $_REQUEST, '');
$warehouse = $modx->getOption('wid', $_REQUEST, 0);

$addr = array();
foreach ($_REQUEST as $k => $v) {
	if (strstr($k, 'addr_') != false) {
		$k = substr($k, 5);
		$addr[$k] = $v;
	}
}

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.orders.item_err_save'));
}

if ($res = $modx->getObject('ModOrders', $id)) {
	$oldstatus = $res->get('status');
	if ($oldstatus != $status) {
		// Сохраняем изменение статуса
		$miniShop = new miniShop($modx);
		$miniShop->changeOrderStatus($id, $status);
	}
	// Пишем поля и сохраняем заказ
	$res->set('comment', $comment);
	$res->set('wid', $warehouse);
	$res->save();
	
	if ($address = $modx->getObject('ModAddress', $addr['id'])) {
		$address->fromArray($addr);
		$address->save();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.orders.item_err_save'));
}

return $modx->error->success('', $res);