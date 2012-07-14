<?php
/**
 * Get a list of Orders
 *
 * @package minishop
 * @subpackage processors
 */

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'created');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$status = $_SESSION['minishop']['status'] = $modx->getOption('status', $scriptProperties, $_SESSION['minishop']['status']);
$query = $modx->getOption('query',$scriptProperties, 0);

$c = $modx->newQuery('ModOrders', array('uid' => $modx->user->id));

if (!empty($status)) {
	$c->andCondition(array('status' => $status));
}

if (!empty($query)) {
	$c->andCondition(array('num:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('ModOrders',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('ModOrders',$c);

$arr = array();
foreach ($orders as $v) {
	$arr[]= array(
		'id' => $v->get('id')
		,'num' => $v->get('num')
		,'sum' => $v->get('sum') + $v->getDeliveryPrice()
		,'weight' => $v->get('weight')
		,'fullname' => $v->getFullName()
		,'warehouse_name' => $v->getWarehouseName()
		,'status' => $v->get('status')
		,'created' => $v->get('created')
		,'updated' => $v->get('updated')
	);
}
return $this->outputArray($arr, $count);
