<?php
/**
 * Get a list of Orders
 *
 * @package minishop
 * @subpackage processors
 */

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'created');
$dir = $modx->getOption('dir',$_REQUEST,'DESC');
$status = $_SESSION['minishop']['status'] = $modx->getOption('status', $_REQUEST, $_SESSION['minishop']['status']);
$query = $modx->getOption('query',$_REQUEST, 0);

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
