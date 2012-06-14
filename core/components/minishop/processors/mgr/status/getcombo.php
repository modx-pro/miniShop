<?php
/**
 * Get a list of Statuses for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$addall = $modx->getOption('addall',$_REQUEST, 0);

$c = $modx->newQuery('ModStatus');
$count = $modx->getCount('ModStatus',$c);

$c->sortby($sort,$dir);
$c->select('id,name');
if ($isLimit) $c->limit($limit,$start);
$items = $modx->getCollection('ModStatus',$c);

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