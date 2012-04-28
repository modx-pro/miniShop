<?php
/**
 * Get a list of History
 *
 * @package minishop
 * @subpackage processors
 */

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'DESC');

$iid = $modx->getOption('iid', $_REQUEST, 0);
$type = $modx->getOption('type', $_REQUEST, 0);
$operation = $modx->getOption('operation', $_REQUEST, 0);

$c = $modx->newQuery('ModLog');
$c->where(array('iid' => $iid));
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
