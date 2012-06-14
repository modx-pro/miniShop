<?php
/**
 * Get a list of Payments
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$query = $modx->getOption('query',$_REQUEST, 0);

$c = $modx->newQuery('ModPayment');
if (!empty($query)) {
	$c->orCondition(array(
		'name:LIKE' => '%'.$query.'%'
		,'description:LIKE' => '%'.$query.'%'
		,'snippet:LIKE' => '%'.$query.'%'
	));
}

$count = $modx->getCount('ModPayment',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$warehouses = $modx->getCollection('ModPayment',$c);

$arr = array();
foreach ($warehouses as $v) {
	$tmp = $v->toArray();
	if ($res = $modx->getObject('modSnippet', $tmp['snippet'])) {
		$tmp['snippet'] = $res->get('name');
	}
	else {
		$tmp['snippet'] = '';
	}
	$arr[]= $tmp;
}
return $this->outputArray($arr, $count);