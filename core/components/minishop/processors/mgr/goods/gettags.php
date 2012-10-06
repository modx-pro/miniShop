<?php
/**
 * Get a list of Goods tags
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$query = $modx->getOption('query', $scriptProperties, 0);
$q = $modx->newQuery('MsTag');

$arr = array();

if (!empty($query)) {
	$q->where(array('tag:LIKE' => '%'.$query.'%'));
	$arr[] = array('tag' => $query);
}
$q->sortby('tag','ASC');
$q->groupby('tag');
$count = $modx->getCount('MsTag', $q);

$q->limit(10);

$res = $modx->getCollection('MsTag', $q);

foreach ($res as $v) {
    $arr[]= array(
		'tag' => $v->get('tag')
	);
}
return $this->outputArray($arr, $count);
