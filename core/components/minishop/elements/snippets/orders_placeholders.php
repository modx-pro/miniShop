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
	unset($tmp['id']);

	$modx->setPlaceholders($tmp);
}
// Плейсхолдеры адреса
if ($address = $modx->getObject('ModAddress', $order->get('address'))) {
	$tmp = $address->toArray();
	unset($tmp['id']);

	$modx->setPlaceholders($tmp);
}

// Плейсхолдеры склада
if ($warehouse = $modx->getObject('ModWarehouse', $order->get('wid'))) {
	$tmp = $warehouse->toArray();
	unset($tmp['id']);

	$modx->setPlaceholders($tmp);
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

		/*
		$tvs = $res->getMany('TemplateVars');
		foreach ($tvs as $v2) {
			$tmp[$v2->get('name')] = $v2->get('value');
		}
		*/
		
		$arr['rows'] .= $modx->getChunk($tplCartRows, $tmp);
		$arr['count'] += $tmp['num'];
		$arr['total'] += $tmp['sum'];
	}
}

$modx->setPlaceholders($arr);
return '';
?>