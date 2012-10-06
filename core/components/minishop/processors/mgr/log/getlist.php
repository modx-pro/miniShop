<?php
/**
 * Get a list of History
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$oid = $modx->getOption('oid', $scriptProperties, 0);
$type = $modx->getOption('type', $scriptProperties, 0);
$operation = $modx->getOption('operation', $scriptProperties, 0);

$c = $modx->newQuery('MsLog');
$c->where(array('oid' => $oid));
if (!empty($type)) {
	$c->andCondition(array('type' => $type));
}
if (!empty($operation)) {
	$c->andCondition(array('operation' => $operation));
}

$count = $modx->getCount('MsLog',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$logs = $modx->getCollection('MsLog',$c);

$arr = array();
foreach ($logs as $v) {
    $tmp = $v->toArray();
	$tmp['username'] = $v->getUserName();
	$tmp['type'] = $modx->lexicon('ms.'.$tmp['type']);
	$tmp['name'] = $v->getName();
	$arr[]= $tmp;
}
return $this->outputArray($arr, $count);
