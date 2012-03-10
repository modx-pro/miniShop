<?php
//echo require $modx->getOption('core_path') . 'components/minishop/elements/snippets/orders_placeholders.php';

if (empty($oid)) {return false;}

$tplCartRows = $modx->getOption('tplRow', $scriptProperties, 'tpl.msOrderEmail.row');

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

$modx->miniShop->initialize();

// Плейсхолдеры заказа
if ($order = $modx->getObject('ModOrders', $oid)) {
	$tmp = $order->toArray();
	$tmp['delivery_name'] = $order->getDeliveryName();
	$tmp['delivery_price'] = $delivery_price = $order->getDeliveryPrice();
	$modx->setPlaceholders($tmp,'order.');
}
// Плейсхолдеры адреса
if ($address = $modx->getObject('ModAddress', $order->get('address'))) {
	$tmp = $address->toArray();
	$modx->setPlaceholders($tmp,'addr.');
}

// Плейсхолдеры склада
if ($warehouse = $modx->getObject('ModWarehouse', $order->get('wid'))) {
	$tmp = $warehouse->toArray();
	$modx->setPlaceholders($tmp,'wh.');
}

// Плейсхолдеры юзера
if ($user = $modx->getObject('modUserProfile', $order->get('uid'))) {
	$tmp = $user->toArray();
	$modx->setPlaceholders($tmp,'user.');
}

// Таблица заказов
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
		
		$tvs = $res->getMany('TemplateVars');
		foreach ($tvs as $v2) {
			$tmp[$v2->get('name')] = $v2->get('value');
		}
		
		$data = json_decode($v->get('data'), 1);
		foreach ($data as $k2 => $v2) {
			$tmp['data.'.$k2] = $v2;
		}

		$arr['rows'] .= $modx->getChunk($tplCartRows, $tmp);
		$arr['count'] += $tmp['num'];
		$arr['total'] += $tmp['sum'];
	}
}
$arr['total'] += $delivery_price;
$modx->setPlaceholders($arr,'cart.');

return '';
?>