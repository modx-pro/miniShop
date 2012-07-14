<?php
/**
 * Get an Order
 * 
 * @package minishop
 * @subpackage processors
 */

$id = $modx->getOption('id',$scriptProperties, 0);

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.order_err_ns'));
}

if ($res = $modx->getObject('ModOrders', $id)) {
	$arr = array();
	$arr['num'] =  $res->get('num');
	$arr['sum'] = $res->get('sum') + $res->getDeliveryPrice();
	$arr['weight'] =  $res->get('weight');
	$arr['created'] =  $res->get('created');
	$arr['fullname'] =  $res->getFullName();
	$arr['email'] =  $res->getEmail();
	$arr['delivery_name'] = $res->getDeliveryName();
	$arr['payment_name'] = $res->getPaymentName();
	$arr['status_name'] =  $res->getStatusName();
	if ($tmp =  $res->getAddress()) {
		foreach($tmp as $k => $v) {
			$arr['addr_'.$k] = $v;
		}
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.order_err_nf'));
}

/* output */
return $modx->error->success('', $arr);
