<?php
/**
 * Remove an Warehouse.
 * 
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModWarehouse', $id)) {
	return $modx->error->failure($modx->lexicon('ms.warehouse.err_nf'));
}

$permission = $res->get('permission');
if (!empty($permission) && !$modx->hasPermission($permission)) {
	return $modx->error->failure($modx->lexicon('ms.no_permission'));
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouse.err_remove'));
}
else {
	$modx->removeCollection('ModDelivery', array('wid' => $id));
	$modx->removeCollection('ModGoods', array('wid' => $id));
}

return $modx->error->success('',$res);