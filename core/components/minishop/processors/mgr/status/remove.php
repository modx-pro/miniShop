<?php
/**
 * Remove an Status.
 * 
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModStatus', $id)) {
	return $modx->error->failure($modx->lexicon('ms.status.err_nf'));
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.status.err_remove'));
}

return $modx->error->success('',$res);