<?php
/**
 * Get a list of Warehouses for cobmobox
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
$query = $modx->getOption('query', $scriptProperties, 0);
$addall = $modx->getOption('addall',$scriptProperties, 0);

$c = $modx->newQuery('ModWarehouse');

if (!empty($query)) {
	$c->andCondition(array('name:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('ModWarehouse',$c);
$c->sortby($sort,$dir);

if ($isLimit) $c->limit($limit,$start);

$res = $modx->getCollection('ModWarehouse',$c);
foreach ($res as $v) {
	$permission = $v->get('permission');
	if (!empty($permission) && !$modx->hasPermission($permission)) {
		continue;
	}
    $tmp = $v->toArray();
	
	$arr[]= $tmp;
}
return $this->outputArray($arr,$count);