<?php
/**
 * Remove an Delivery.
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('MsDelivery', $id)) {
	return $modx->error->failure($modx->lexicon('ms.delivery.err_nf'));
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.delivery.err_remove'));
}

return $modx->error->success('',$res);
