<?php
/**
 * Get a list of Warehouses
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

$c = $modx->newQuery('ModWarehouse');
if (!empty($query)) {
	$c->orCondition(array(
		'name:LIKE' => '%'.$query.'%'
		,'address:LIKE' => '%'.$query.'%'
		,'description:LIKE' => '%'.$query.'%'
		,'phone:LIKE' => '%'.$query.'%'
		,'email:LIKE' => '%'.$query.'%'
	));
}

$count = $modx->getCount('ModWarehouse',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$warehouses = $modx->getCollection('ModWarehouse',$c);

$arr = array();
foreach ($warehouses as $v) {
	$permission = $v->get('permission');
	if (!empty($permission) && !$modx->hasPermission($permission)) {
		continue;
	}
    $tmp = $v->toArray();
	//$tmp['fullname'] = $v->getFullName();
	//$tmp['warehousename'] = $v->getWarehouseName();
	//$tmp['statusname'] = $v->getStatusName();
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);