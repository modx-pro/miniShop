<?php
if (empty($oid)) {return false;}

$tplCartRows = $modx->getOption('tplRow', $scriptProperties, 'tpl.msOrderEmail.row');

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

// Placeholders of order
if ($order = $modx->getObject('ModOrders', $oid)) {
	$tmp = $order->toArray();
	$tmp['delivery_name'] = $order->getDeliveryName();
	$tmp['payment_name'] = $order->getPaymentName();
	$tmp['delivery_price'] = $delivery_price = $order->getDeliveryPrice();
	$modx->setPlaceholders($tmp,'order.');
}
// Placeholders of address
if ($address = $modx->getObject('ModAddress', $order->get('address'))) {
	$tmp = $address->toArray();
	$modx->setPlaceholders($tmp,'addr.');
}

// Placeholders of warehouse
if ($warehouse = $modx->getObject('ModWarehouse', $order->get('wid'))) {
	$tmp = $warehouse->toArray();
	$modx->setPlaceholders($tmp,'wh.');
}

// Placeholders of user
if ($user = $modx->getObject('modUserProfile', $order->get('uid'))) {
	$tmp = $user->toArray();
	$modx->setPlaceholders($tmp,'user.');
}

// Table with ordered goods
$arr = array();
$arr['rows'] = '';
$arr['count'] = $arr['total'] = 0;
$cart = $modx->getCollection('ModOrderedGoods', array('oid' => $order->get('id')));
foreach ($cart as $v) {
	if ($res = $modx->getObject('modResource', $v->get('gid'))) {
		$tmp = $res->toArray();
		$tmp['num'] = $v->get('num');
		$tmp['sum'] = $v->get('sum');
		$tmp['price'] = $v->get('price');
		$tmp['weight'] = $v->get('weight');
		
		$tvs = $res->getMany('TemplateVars');
		foreach ($tvs as $v2) {
			$tmp[$v2->get('name')] = $v2->get('value');
		}
		
		$data = json_decode($v->get('data'), 1);
		if (is_array($data) && !empty($data)) {
			foreach ($data as $k2 => $v2) {
				$tmp['data.'.$k2] = $v2;
			}
		}

		$arr['rows'] .= $modx->getChunk($tplCartRows, $tmp);
		$arr['count'] += $tmp['num'];
		$arr['total'] += $tmp['sum'];
		$arr['weight'] += $tmp['weight'];
	}
}
$arr['total'] += $delivery_price;
$modx->setPlaceholders($arr,'cart.');

return '';
