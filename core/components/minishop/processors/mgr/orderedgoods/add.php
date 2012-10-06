<?php
/**
 * Create an OrderedGoods
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Checking for required fields
if (empty($scriptProperties['gid'])) {
	$modx->error->addField('gid',$modx->lexicon('ms.required_field'));
}
if (empty($scriptProperties['num'])) {
	$modx->error->addField('num',$modx->lexicon('ms.required_field'));
}
else if ($scriptProperties['num'] < 0) {
	$scriptProperties['num'] = 0;
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}
if (!empty($scriptProperties['data'])) {
	if (!json_decode($scriptProperties['data'], true) && $scriptProperties['data'] != '[]') {
		 return $modx->error->failure($modx->lexicon('ms.orderedgoods.err_data'));
	}
}

// Loading miniShop class
$miniShop = new miniShop($modx);

if ($res = $modx->getObject('modResource', $scriptProperties['gid'])) {
	$price = !empty($scriptProperties['price']) ? $scriptProperties['price'] : $miniShop->getPrice($scriptProperties['gid']);
	$weight = !empty($scriptProperties['weight']) ? $scriptProperties['weight'] : $miniShop->getWeight($scriptProperties['gid']);
	$sum = $scriptProperties['num'] * $price;

	$goods = $modx->newObject('MsOrderedGood');
	$goods->fromArray(array(
		'gid' => $scriptProperties['gid']
		,'oid' => $scriptProperties['oid']
		,'num' => $scriptProperties['num']
		,'price' => $price
		,'weight' => $weight
		,'sum' => $sum
		,'data' => !empty($scriptProperties['data']) ? $scriptProperties['data'] : json_encode(array())
	));
	$goods->save();
	$miniShop->Log('goods', $scriptProperties['oid'], $scriptProperties['gid'], 'add', 0, $scriptProperties['num'], 'Added '.$scriptProperties['num'].' items of "'. $res->get('pagetitle').'"');

	if ($order = $modx->getObject('MsOrder', $scriptProperties['oid'])) {
		$order->updateSum();
		$order->updateWeight();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);
