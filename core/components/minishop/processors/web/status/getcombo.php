<?php
/**
 * Get a list of Statuses for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$addall = $modx->getOption('addall',$scriptProperties, 0);

$c = $modx->newQuery('MsStatus');
$count = $modx->getCount('MsStatus',$c);

$c->sortby($sort,$dir);
$c->select('id,name');
if ($isLimit) $c->limit($limit,$start);
$items = $modx->getCollection('MsStatus',$c);

if (!empty($addall)) {
	$list = array(array('id' => 0, 'name' => $modx->lexicon('ms.combo.all')));
}
else {
	$list = array();
}

foreach ($items as $item) {
    $itemArray = $item->toArray();
    $list[]= $itemArray;
}
return $this->outputArray($list,$count);
