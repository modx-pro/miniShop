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
$delivery = $modx->getOption('delivery', $_REQUEST, 0);
$payment = $modx->getOption('payment', $_REQUEST, 0);

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

$miniShop = new miniShop($modx);

if ($res = $modx->getObject('ModOrders', $id)) {
	$oldstatus = $res->get('status');
	$olddelivery = $res->get('delivery');
	$oldpayment = $res->get('payment');
	$oldwarehouse = $res->get('wid');
	
	// Смена склада
	if ($delivery > 0 && $warehouse != $oldwarehouse) {
		if ($tmp = $modx->getObject('ModWarehouse', $warehouse)) {
			$deliveries = $tmp->getDeliveries();
			if ($delivery > 0 && !in_array($delivery, $deliveries)) {
				return $modx->error->failure($modx->lexicon('ms.delivery.err_save'));
			}
			
		}
		else {
			return $modx->error->failure($modx->lexicon('ms.warehouse.err_nf'));
		}
	}
	if ($warehouse != $oldwarehouse) {
		$change_warehouse = 1;
		$res->set('wid', $warehouse);
	}
	// Смена способа доставки и проверка метода оплаты
	if ($delivery > 0) {
		if ($tmp = $modx->getObject('ModDelivery', $delivery)) {
			$payments = $tmp->getPayments();
			if (!in_array($payment, $payments)) {
				return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
			}
		}
		else {
			return $modx->error->failure($modx->lexicon('ms.delivery.err_nf'));
		}
	}
	if ($delivery != $olddelivery) {
		$change_delivery = 1;
		$res->set('delivery', $delivery);
	}
	// Смена способа платежа
	if ($payment != $oldpayment) {
		$change_payment = 1;
		$res->set('payment', $payment);
	}
	// Пишем поля и сохраняем заказ
	$res->set('comment', $comment);
	if  ($res->save()) {
		if ($change_warehouse) {$miniShop->Log('warehouse', $id, 'change', $oldwarehouse, $warehouse);}
		if ($change_delivery) {$miniShop->Log('delivery', $id, 'change', $oldelivery, $delivery);}
		if ($change_payment) {$miniShop->Log('payment', $id, 'change', $oldpayment, $payment);}
	}
	
	if ($address = $modx->getObject('ModAddress', $addr['id'])) {
		$address->fromArray($addr);
		$address->save();
	}
	// Смена статуса
	if ($oldstatus != $status) {
		$miniShop->changeOrderStatus($id, $status);
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.orders.item_err_save'));
}

return $modx->error->success('', $res);