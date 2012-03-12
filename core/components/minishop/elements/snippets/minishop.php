<?php
if (empty($_REQUEST['action'])) {
	$action = $modx->getOption('action', $scriptProperties, 'getCart');
}
else {
	$action = $_REQUEST['action'];
}

// Шаблоны оформления
$c['tplCartOuter'] = $modx->getOption('tplCartOuter', $scriptProperties, 'tpl.msCart.outer');
$c['tplCartRow'] = $modx->getOption('tplCartRow', $scriptProperties, 'tpl.msCart.row');
$c['tplCartStatus'] = $modx->getOption('tplCartStatus', $scriptProperties, 'tpl.msCart.status');

$c['tplDeliveryOuter'] = $modx->getOption('tplDeliveryOuter', $scriptProperties, 'tpl.msDelivery.outer');
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
//$c['tplSubmitOrderError'] = $modx->getOption('tplSubmitOrderError', $scriptProperties, 'tpl.msSubmitOrder.error');

$c['tplMyOrdersList'] = $modx->getOption('tplMyOrdersList', $scriptProperties, 'tpl.msMyOrdersList');

$miniShop = $modx->getService('minishop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/',$c);
if (!($miniShop instanceof miniShop)) return '';

// Вызов нужного метода
switch ($action) {
	case 'getCart': $res = $miniShop->getCart(); break;
	case 'addToCart': $res = $miniShop->addToCart($_POST['gid'], $_POST['num'], $_POST['data']); break;
	case 'remFromCart': $res = $miniShop->remFromCart($_POST['key']); break;
	case 'changeCartCount': $res = $miniShop->changeCartCount($_POST['key'], $_POST['val']); break;
	case 'getCartStatus': $res = $miniShop->getCartStatus(); break;
	
	case 'selectDelivery': $res = $miniShop->selectDelivery(); break; 
	case 'saveDelivery': $res = $miniShop->saveDelivery($_POST['id']); break; 

	case 'getAddrForm': $res = $miniShop->getAddrForm(); break;
	case 'saveAddrForm': $res = $miniShop->saveAddrForm(); break;
	
	case 'confirmOrder': $res = $miniShop->confirmOrder(); break;
	
	case 'submitOrder': $res = $miniShop->submitOrder(); break;
	
	case 'getMyOrdersList': $res = $miniShop->getMyOrdersList(); break;

	//ExtJS
	case 'orders/getlist': echo $modx->runProcessor('web/orders/getlist', array(), array('processors_path' => $miniShop->config['processorsPath']))->response; die;
	case 'status/getcombo': echo $modx->runProcessor('web/status/getcombo', array(), array('processors_path' => $miniShop->config['processorsPath']))->response; die;
	case 'orders/get': $res = $modx->runProcessor('web/orders/get', array(), array('processors_path' => $miniShop->config['processorsPath'])); echo json_encode($res->response); die;
	
	case 'orderedgoods/getlist': echo $modx->runProcessor('web/orderedgoods/getlist', array(), array('processors_path' => $miniShop->config['processorsPath']))->response; die;
	case 'log/getlist': echo $modx->runProcessor('web/log/getlist', array(), array('processors_path' => $miniShop->config['processorsPath']))->response; die;
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