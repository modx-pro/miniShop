<?php
/**
 * Get a list of Statuses
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$c = $modx->newQuery('ModStatus');
$count = $modx->getCount('ModStatus',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$warehouses = $modx->getCollection('ModStatus',$c);

$arr = array();
foreach ($warehouses as $v) {
	$arr[]= $v->toArray();
}
return $this->outputArray($arr, $count);