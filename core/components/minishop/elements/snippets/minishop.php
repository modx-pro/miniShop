<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
// Defining action. If no action in $_REQUEST - set default (getCart)
if (empty($_REQUEST['action'])) {$action = $modx->getOption('action', $scriptProperties, 'getCart');}
else {$action = $_REQUEST['action'];}

// Load class
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
    $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
  if (!($modx->miniShop instanceof miniShop)) return '';
}

// Load needed method
switch ($action) {
	case 'getCart': $res = $modx->miniShop->getCart(); break;
	case 'getMiniCart': $res = $modx->miniShop->getMiniCart(); break;
	case 'addToCart': $res = $modx->miniShop->addToCart($_POST['gid'], $_POST['num'], $_POST['data']); break;
	case 'remFromCart': $res = $modx->miniShop->remFromCart($_POST['key']); break;
	case 'changeCartCount': $res = $modx->miniShop->changeCartCount($_POST['key'], $_POST['val']); break;
	case 'getCartStatus': $res = $modx->miniShop->getCartStatus(); break;
	case 'getDelivery': $res = $modx->miniShop->getDelivery(); break;
	case 'getPayments': $res = $modx->miniShop->getPayments(); break;
	case 'submitOrder': $res = $modx->miniShop->submitOrder(); break;
	case 'getMyOrdersList': $res = $modx->miniShop->getMyOrdersList(); break;
	case 'redirectCustomer': $res = $modx->miniShop->redirectCustomer($_REQUEST['oid'], $_REQUEST['email']); break;
	case 'receivePayment': $res = $modx->miniShop->receivePayment($_REQUEST); break;

	//ExtJS connectors
	case 'orders/getlist': echo $modx->runProcessor('web/orders/getlist', $_REQUEST, array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'status/getcombo': echo $modx->runProcessor('web/status/getcombo', $_REQUEST, array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'orders/get': $res = $modx->runProcessor('web/orders/get', $_REQUEST, array('processors_path' => $modx->miniShop->config['processorsPath'])); echo json_encode($res->response); die;
	case 'orderedgoods/getlist': echo $modx->runProcessor('web/orderedgoods/getlist', $_REQUEST, array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	case 'log/getlist': echo $modx->runProcessor('web/log/getlist', $_REQUEST, array('processors_path' => $modx->miniShop->config['processorsPath']))->response; die;
	//
    default: $res = '';
}

// Returning results, according to request typea
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
else if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && empty($res)) {}
else {
	return $res;
}
