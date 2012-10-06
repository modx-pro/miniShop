<?php
/**
 * Get a list of Delivery for cobmobox
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

$c = $modx->newQuery('MsDelivery');

if (!empty($query)) {
	$c->andCondition(array('name:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('MsDelivery',$c);
$c->sortby($sort,$dir);
$c->select('MsDelivery.id,MsDelivery.name');
if ($isLimit) $c->limit($limit,$start);

$res = $modx->getCollection('MsDelivery',$c);
$arr = array(array(
	'id' => '0'
	,'name' => $modx->lexicon('no')
));
foreach ($res as $v) {
	$arr[]= $v->toArray();
}
return $this->outputArray($arr,$count);
