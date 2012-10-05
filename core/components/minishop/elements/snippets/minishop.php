<?php
/**
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
// Defining action. If no action in $_REQUEST - set default (getCart)
if (empty($_REQUEST['action'])) {$action = $modx->getOption('action', $scriptProperties, 'getCart');}
else {$action = $_REQUEST['action'];}

$ms = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
if (!($ms instanceof miniShop)) return '';
$ms->config = array_merge($ms->config, $scriptProperties);

// Load needed method
switch ($action) {
    case 'getCart': $res = $ms->getCart(); break;
    case 'getMiniCart': $res = $ms->getMiniCart(); break;
    case 'addToCart': $res = $ms->addToCart($_POST['gid'], $_POST['num'], $_POST['data']); break;
    case 'remFromCart': $res = $ms->remFromCart($_POST['key']); break;
    case 'changeCartCount': $res = $ms->changeCartCount($_POST['key'], $_POST['val']); break;
    case 'getCartStatus': $res = $ms->getCartStatus(); break;
    case 'getDelivery': $res = $ms->getDelivery(); break;
    case 'getPayments': $res = $ms->getPayments(); break;
    case 'submitOrder': $res = $ms->submitOrder(); break;
    case 'getMyOrdersList': $res = $ms->getMyOrdersList(); break;
    case 'redirectCustomer': $res = $ms->redirectCustomer($_REQUEST['oid'], $_REQUEST['email']); break;
    case 'receivePayment': $res = $ms->receivePayment($_REQUEST); break;

    //ExtJS connectors
    case 'orders/getlist': echo $modx->runProcessor('web/orders/getlist', $_REQUEST, array('processors_path' => $ms->config['processorsPath']))->response; die;
    case 'status/getcombo': echo $modx->runProcessor('web/status/getcombo', $_REQUEST, array('processors_path' => $ms->config['processorsPath']))->response; die;
    case 'orders/get': $res = $modx->runProcessor('web/orders/get', $_REQUEST, array('processors_path' => $ms->config['processorsPath'])); echo json_encode($res->response); die;
    case 'orderedgoods/getlist': echo $modx->runProcessor('web/orderedgoods/getlist', $_REQUEST, array('processors_path' => $ms->config['processorsPath']))->response; die;
    case 'log/getlist': echo $modx->runProcessor('web/log/getlist', $_REQUEST, array('processors_path' => $ms->config['processorsPath']))->response; die;
    //
    default: $res = '';
}

// Returning results, according to request typea
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && !empty($res)) {
    if (!$_REQUEST['json_encode']) {
        echo json_encode($res);
    } else {
        $maxIterations= (integer) $modx->getOption('parser_max_iterations', null, 10);
        $modx->getParser()->processElementTags('', $res, false, false, '[[', ']]', array(), $maxIterations);
        $modx->getParser()->processElementTags('', $res, true, true, '[[', ']]', array(), $maxIterations);
        echo $res;
    }
    die;
} else if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && empty($res)) {

}

return $res;
