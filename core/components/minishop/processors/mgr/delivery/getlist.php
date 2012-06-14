<?php
/**
 * Get a list of Deliveries methods
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
$wid = $modx->getOption('wid',$_REQUEST,0);

$query = $modx->getOption('query',$_REQUEST, 0);
$c = $modx->newQuery('ModDelivery');
$c->where(array('wid' => $wid));

$count = $modx->getCount('ModDelivery',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$delivery = $modx->getCollection('ModDelivery',$c);

$arr = array();
foreach ($delivery as $v) {
    $tmp = $v->toArray();
	//$tmp['fullname'] = $v->getFullName();
	//$tmp['warehousename'] = $v->getWarehouseName();
	//$tmp['statusname'] = $v->getStatusName();
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);
