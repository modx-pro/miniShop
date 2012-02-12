<?php
//include $modx->getOption('minishop.core_path') . 'elements/snippets/minishop.php';

if (empty($_REQUEST['action'])) {
	$action = $modx->getOption('action', $scriptProperties, 'getCart');
}
else {
	$action = $_REQUEST['action'];
}

// Страницы работы с заказом
$c['page_cart'] = 2;
//$c['page_delivery'] = 293;
//$c['page_address'] = 351;
//$c['page_confirm'] = 291;

// Сессия и ТВ с ценой
$c['sess_name'] = 'minishop';

// Текущий склад
$c['warehouse'] = $_SESSION['minishop']['warehouse'];


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
 
// Вывод ошибок
ini_set('display_errors', 1); 
error_reporting(E_ALL); 
 
 
$miniShop = $modx->getService('minishop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/',$c);
if (!($miniShop instanceof miniShop)) return '';

// Вызов нужного метода
switch ($action) {
	case 'getCart': $res = $miniShop->getCart(); break;
	case 'addToCart': $res = $miniShop->addToCart($_POST['gid']); break;
	case 'remFromCart': $res = $miniShop->remFromCart($_POST['gid']); break;
	case 'changeCartCount': $res = $miniShop->changeCartCount($_POST['gid'], $_POST['val']); break;
	case 'getCartStatus': $res = $miniShop->getCartStatus(); break;
	
	case 'selectDelivery': $res = $miniShop->selectDelivery(); break; 
	case 'saveDelivery': $res = $miniShop->saveDelivery($_POST['id']); break; 

	case 'getAddrForm': $res = $miniShop->getAddrForm(); break;
	case 'saveAddrForm': $res = $miniShop->saveAddrForm(); break;
	
	case 'confirmOrder': $res = $miniShop->confirmOrder(); break;
	
	case 'submitOrder': $res = $miniShop->submitOrder($_POST['captcha']); break;
    default: $res = '';
}

// Вывод ответа, в зависимости от типа запроса
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && !empty($res)) {
	if (!$_REQUEST['json_encode']) {
		return json_encode($res);
	}
	else {
		$maxIterations= (integer) $modx->getOption('parser_max_iterations', null, 10);
		$modx->getParser()->processElementTags('', $res, false, false, '[[', ']]', array(), $maxIterations);
		$modx->getParser()->processElementTags('', $res, true, true, '[[', ']]', array(), $maxIterations);
		return $res;
	}
}
else {
	return $res;
}
