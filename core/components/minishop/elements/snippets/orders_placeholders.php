<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
if (empty($oid)) {return false;}

$tplCartRows = $modx->getOption('tplRow', $scriptProperties, 'tpl.msOrderEmail.row');

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
    $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

// Placeholders of order
/** @var ModOrders $order */
if ($order = $modx->getObject('ModOrders', $oid)) {
	$tmp = $order->toArray();
	$tmp['delivery_name'] = $order->getDeliveryName();
	$tmp['payment_name'] = $order->getPaymentName();
	$tmp['delivery_price'] = $delivery_price = $order->getDeliveryPrice();
	$modx->setPlaceholders($tmp,'order.');
}
// Placeholders of address
/** @var ModAddress $address */
if ($address = $modx->getObject('ModAddress', $order->get('address'))) {
	$tmp = $address->toArray();
	$modx->setPlaceholders($tmp,'addr.');
}

// Placeholders of warehouse
/** @var ModWarehouse $warehouse */
if ($warehouse = $modx->getObject('ModWarehouse', $order->get('wid'))) {
	$tmp = $warehouse->toArray();
	$modx->setPlaceholders($tmp,'wh.');
}

// Placeholders of user
/** @var modUserProfile $user */
if ($user = $modx->getObject('modUserProfile', $order->get('uid'))) {
	$tmp = $user->toArray();
	$modx->setPlaceholders($tmp,'user.');
}

// Table with ordered goods
$arr = array();
$arr['rows'] = '';
$arr['count'] = $arr['total'] = 0;
$cart = $modx->getCollection('ModOrderedGoods', array('oid' => $order->get('id')));
/** @var ModOrderedGoods $v */
foreach ($cart as $v) {
    $ordered = $v->toArray();
    unset($ordered['id']);
    $res = $res = $modx->miniShop->getProduct($ordered['gid'], $order->get('wid'), 2);
    if (count($res)) {
        $tmp = array_merge($res, $ordered);

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
$arr['total_all'] = $arr['total'] + $delivery_price;
$modx->setPlaceholders($arr,'cart.');

return '';
