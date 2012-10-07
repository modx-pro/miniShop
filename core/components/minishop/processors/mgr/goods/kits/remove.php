<?php
/**
 * Remove an Product from Kit.
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];

if ($res = $modx->getObject('MsKit', $id)) {
	$res->remove();
	return $modx->error->success('',$res);
}

return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
