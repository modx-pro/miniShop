<?php
// Определение действия сниппета
if (empty($_REQUEST['action'])) {$action = $modx->getOption('action', $scriptProperties, 'getCart');}
else {$action = $_REQUEST['action'];}

// Чанки оформления
$c['tplCartOuter'] = $modx->getOption('tplCartOuter', $scriptProperties, 'tpl.msCart.outer');
$c['tplCartRow'] = $modx->getOption('tplCartRow', $scriptProperties, 'tpl.msCart.row');
$c['tplCartStatus'] = $modx->getOption('tplCartStatus', $scriptProperties, 'tpl.msCart.status');
$c['tplDeliveryRow'] = $modx->getOption('tplDeliveryRow', $scriptProperties, 'tpl.msDelivery.row');
$c['tplAddrForm'] = $modx->getOption('tplAddrForm', $scriptProperties, 'tpl.msAddrForm');
$c['tplAddrFormMini'] = $modx->getOption('tplAddrFormMini', $scriptProperties, 'tpl.msAddrForm.mini');
$c['tplAddrFormSaved'] = $modx->getOption('tplAddrFormSaved', $scriptProperties, 'tpl.msAddrForm.saved');
$c['tplConfirmOrder'] = $modx->getOption('tplConfirmOrder', $scriptProperties, 'tpl.msConfirmOrder');
$c['tplConfirmOrderRow'] = $modx->getOption('tplConfirmOrderRow', $scriptProperties, 'tpl.msConfirmOrder.row');
$c['tplOrderEmailUser'] = $modx->getOption('tplOrderEmailUser', $scriptProperties, 'tpl.msOrderEmail.user');
$c['tplOrderEmailManager'] = $modx->getOption('tplOrderEmailManager', $scriptProperties, 'tpl.msOrderEmail.manager');
$c['tplOrderEmailRow'] = $modx->getOption('tplOrderEmailRow', $scriptProperties, 'tpl.msOrderEmail.row');
$c['tplSubmitOrderSuccess'] = $modx->getOption('tplSubmitOrderSuccess', $scriptProperties, 'tpl.msSubmitOrder.success');
$c['tplMyOrdersList'] = $modx->getOption('tplMyOrdersList', $scriptProperties, 'tpl.msMyOrdersList'); 
$c['tplPaymentForm'] = $modx->getOption('tplPaymentForm', $scriptProperties, 'tpl.msPayment.form');

// Группы для регистрации покупателей
$c['userGroups'] = $modx->getOption('userGroups', $scriptProperties, 0);

// Подключение класса
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
  $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $c);
  if (!($modx->miniShop instanceof miniShop)) return '';
}

// Вызов нужного метода
switch ($action) {
	case 'getCart': $res = $modx->miniShop->getCart(); break;
	case 'addToCart': $res = $modx->miniShop->addToCart($_POST['gid'], $_POST['num'], $_POST['data']); break;
	case 'remFromCart': $res = $modx->miniShop->remFromCart($_POST['key']); break;
	case 'changeCartCount': $res = $modx->miniShop->changeCartCount($_POST['key'], $_POST['val']); break;
	case 'getCartStatus': $res = $modx->miniShop->getCartStatus(); break;
	case 'getDelivery': $res = $modx->miniShop->getDelivery(); break; 
	case 'submitOrder': $res = $modx->miniShop->submitOrder(); break;
	case 'getMyOrdersList': $res = $modx->miniShop->getMyOrdersList(); break;
	case 'redirectCustomer': $res = $modx->miniShop->redirectCustomer($_REQUEST['oid'], $_REQUEST['email']); break;
	case 'receivePayment': $res = $modx->miniShop->receivePayment($_REQUEST); break;
  
	//ExtJS connectors
	case 'orders/getlist': echo $modx->runProcessor('web/orders/getlist', array(), array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'status/getcombo': echo $modx->runProcessor('web/status/getcombo', array(), array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'orders/get': $res = $modx->runProcessor('web/orders/get', array(), array('processors_path' => $modx->miniShop->config['processorsPath'])); echo json_encode($res->response); die;
	case 'orderedgoods/getlist': echo $modx->runProcessor('web/orderedgoods/getlist', array(), array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'log/getlist': echo $modx->runProcessor('web/log/getlist', array(), array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	//
    default: $res = '';
}

// Вывод ответа, в зависимости от типа запроса
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && !empty($res)) {
	if (!$_REQUEST['json_encode']) {
		echo json_encode($res);
	}
	else {
		$maxIterations= (integer) $modx->getOption('parser_max_iterations', null, 10);
		$modx->getParser()->processElementTags('', $res, false, false, '[[', ']]', array(), $maxIterations);
		$modx->getParser()->processElementTags('', $res, true, true, '[[', ']]', array(), $maxIterations);
		echo $res;
	}
	die;
}
else {
	return $res;
}