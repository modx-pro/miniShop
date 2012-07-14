<?php
/**
 * Get a list of Payments for current Delivery
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$did = $modx->getOption('delivery',$scriptProperties,0);

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