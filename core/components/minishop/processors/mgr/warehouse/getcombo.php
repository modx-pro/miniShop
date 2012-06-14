<?php
/**
 * Get a list of Warehouses for cobmobox
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
$addall = $modx->getOption('addall',$_REQUEST, 0);

$c = $modx->newQuery('ModWarehouse');

if (!empty($query)) {
	$c->andCondition(array('name:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('ModWarehouse',$c);
$c->sortby($sort,$dir);

if ($isLimit) $c->limit($limit,$start);

$res = $modx->getCollection('ModWarehouse',$c);
/*
if (!empty($addall)) {
	$list = array(array('id' => 0, 'name' => $modx->lexicon('ms.combo.all')));
}
else {
	$list = array();
}
*/
foreach ($res as $v) {
	$permission = $v->get('permission');
	if (!empty($permission) && !$modx->hasPermission($permission)) {
		continue;
	}
    $tmp = $v->toArray();
	
	$arr[]= $tmp;
}
return $this->outputArray($arr,$count);