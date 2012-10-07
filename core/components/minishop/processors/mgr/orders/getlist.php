<?php
/**
 * Get a list of Orders
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$scriptProperties,'created');

$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$warehouse = $modx->getOption('warehouse', $scriptProperties, $_SESSION['minishop']['warehouse']);
$status = $modx->getOption('status', $scriptProperties, $_SESSION['minishop']['status']);
$_SESSION['minishop']['warehouse'] = $warehouse;
$_SESSION['minishop']['status'] = $status;

$query = $modx->getOption('query',$scriptProperties, 0);
$c = $modx->newQuery('MsOrder');

if (!empty($status)) {
	$c->andCondition(array('status' => $status));
}

if (!empty($query)) {
	$c->andCondition(array('num:LIKE' => '%'.$query.'%'));
}
if (!empty($warehouse)) {
	$c->andCondition(array('wid' => $warehouse));
}

$count = $modx->getCount('MsOrder',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('MsOrder',$c);

$arr = array();
foreach ($orders as $v) {
    $tmp = $v->toArray();
	$tmp['fullname'] = $v->getFullName();
	$tmp['warehousename'] = $v->getWarehouseName();
	$tmp['sum'] += $v->getDeliveryPrice();
	$arr[]= $tmp;

}
return $this->outputArray($arr, $count);
