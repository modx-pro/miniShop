<?php
/**
 * Create an OrderedGoods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Checking for required fields
if (empty($_POST['gid'])) {
	$modx->error->addField('gid',$modx->lexicon('ms.required_field'));
}
if (empty($_POST['num'])) {
	$modx->error->addField('num',$modx->lexicon('ms.required_field'));
}
else if ($_POST['num'] < 0) {
	$_POST['num'] = 0;
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}
if (!empty($_POST['data'])) {
	if (!json_decode($_POST['data'], true) && $_POST['data'] != '[]') {
		 return $modx->error->failure($modx->lexicon('ms.orderedgoods.err_data'));
	}
}

// Loading miniShop class
$miniShop = new miniShop($modx);

if ($res = $modx->getObject('modResource', $_POST['gid'])) {
	$price = !empty($_POST['price']) ? $_POST['price'] : $miniShop->getPrice($_POST['gid']);
	$weight = !empty($_POST['weight']) ? $_POST['weight'] : $miniShop->getWeight($_POST['gid']);
	$sum = $_POST['num'] * $price;
	
	$goods = $modx->newObject('ModOrderedGoods');
	$goods->fromArray(array(
		'gid' => $_POST['gid']
		,'oid' => $_POST['oid']
		,'num' => $_POST['num']
		,'price' => $price
		,'weight' => $weight
		,'sum' => $sum
		,'data' => !empty($_POST['data']) ? $_POST['data'] : json_encode(array())
	));
	$goods->save();
	$miniShop->Log('goods', $_POST['oid'], $_POST['gid'], 'add', 0, $_POST['num'], 'Added '.$_POST['num'].' items of "'. $res->get('pagetitle').'"');

	if ($order = $modx->getObject('ModOrders', $_POST['oid'])) {
		$order->updateSum();
		$order->updateWeight();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);
