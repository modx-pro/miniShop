<?php
/**
 * Remove an Order.
 * 
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$order = $modx->getObject('ModOrders', $id)) {
	return $modx->error->failure($modx->lexicon('ms.orders.err_nf'));
}
if ($modx->getOption('minishop.enable_remains')) {
	$order->releaseReserved();
}
$modx->invokeEvent('msOnBeforeOrderDelete', array('order' => $order));
if ($order->remove()) {
	$modx->removeCollection('ModOrderedGoods', array('oid' => $id));
	$modx->removeCollection('ModLog', array('iid' => $id, 'type' => 'status'));
	$modx->invokeEvent('msOnOrderDelete', array());
}
else {
	return $modx->error->failure($modx->lexicon('ms.orders.err_remove'));
}

return $modx->error->success('',$order);