<?php
/**
 * Get a list of History
 *
 * @package minishop
 * @subpackage processors
 */

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$oid = $modx->getOption('oid', $scriptProperties, 0);
$type = $modx->getOption('type', $scriptProperties, 0);
$operation = $modx->getOption('operation', $scriptProperties, 0);

$c = $modx->newQuery('ModLog');
$c->where(array('oid' => $oid));
if (!empty($type)) {
	$c->andCondition(array('type' => $type));
}
if (!empty($operation)) {
	$c->andCondition(array('operation' => $operation));
}

$count = $modx->getCount('ModLog',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$logs = $modx->getCollection('ModLog',$c);

$arr = array();
foreach ($logs as $v) {
	$arr[] = array(
		'type' => $modx->lexicon('ms.'.$v->get('type'))
		,'name' => $v->getName()
		,'timestamp' => $v->get('timestamp')
	);
}
return $this->outputArray($arr, $count);
