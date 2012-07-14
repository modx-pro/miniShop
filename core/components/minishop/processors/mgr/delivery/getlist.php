<?php
/**
 * Get a list of Deliveries methods
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
$wid = $modx->getOption('wid',$scriptProperties,0);

$query = $modx->getOption('query',$scriptProperties, 0);
$c = $modx->newQuery('ModDelivery');
$c->where(array('wid' => $wid));

$count = $modx->getCount('ModDelivery',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$delivery = $modx->getCollection('ModDelivery',$c);

$arr = array();
foreach ($delivery as $v) {
    $tmp = $v->toArray();
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);
