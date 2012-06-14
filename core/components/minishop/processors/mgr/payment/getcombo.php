<?php
/**
 * Get a list of Payments for cobmobox
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
$query = $modx->getOption('query', $_REQUEST, 0);

$c = $modx->newQuery('ModPayment');

if (!empty($query)) {
	$c->andCondition(array('name:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('ModPayment',$c);
$c->sortby($sort,$dir);
$c->select('ModPayment.id,ModPayment.name');
if ($isLimit) $c->limit($limit,$start);

$res = $modx->getCollection('ModPayment',$c);
$arr = array(array(
	'id' => '0'
	,'name' => $modx->lexicon('no')
));
foreach ($res as $v) {
	$arr[]= $v->toArray();
}
return $this->outputArray($arr,$count);