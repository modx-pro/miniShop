<?php
/**
 * Remove an Order.
 * 
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModOrders', $id)) {
	return $modx->error->failure($modx->lexicon('ms.orders.err_nf'));
}
if ($modx->getOption('minishop.enable_remains')) {
	$res->releaseReserved();
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.orders.err_remove'));
}
else {
	$modx->removeCollection('ModOrderedGoods', array('oid' => $id));
	$modx->removeCollection('ModLog', array('iid' => $id, 'type' => 'status'));
}

return $modx->error->success('',$res);