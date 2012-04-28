<?php
/**
 * Delete an OrderedGoods.
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModOrderedGoods', $id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
$oid = $res->get('oid');
$res->remove();

if ($order = $modx->getObject('ModOrders', $oid)) {
	$order->updateSum();
	$order->updateWeight();
}

return $modx->error->success('',$res);
