<?php
/**
 * Get a list of Payments for current Delivery
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$did = $modx->getOption('delivery',$_REQUEST,0);
//$query = $modx->getOption('query',$_REQUEST, 0);

if (empty($did)) {
	return $modx->error->failure($modx->lexicon('ms.delivery.err_nf'));
}
else {
	if (!$delivery = $modx->getObject('ModDelivery', $did)) {
		return $modx->error->failure($modx->lexicon('ms.delivery.err_nf'));
	}
}
$cur_payments = $delivery->getPayments();

$c = $modx->newQuery('ModPayment');
/*
if (!empty($query)) {
	$c->orCondition(array(
		'name:LIKE' => '%'.$query.'%'
		,'description:LIKE' => '%'.$query.'%'
		,'snippet:LIKE' => '%'.$query.'%'
	));
}
*/

$count = $modx->getCount('ModPayment',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$payments = $modx->getCollection('ModPayment',$c);

$arr = array();
foreach ($payments as $v) {
	$tmp = $v->toArray();
	if ($res = $modx->getObject('modSnippet', $tmp['snippet'])) {
		$tmp['snippet'] = $res->get('name');
	}
	else {
		$tmp['snippet'] = '';
	}
	$tmp['enabled'] = in_array($tmp['id'], $cur_payments) ? 1 : 0;
	
	$arr[]= $tmp;
}
return $this->outputArray($arr, $count);