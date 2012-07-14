<?php
/**
 * Get a list of Warehouses
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties, 0);

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
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);