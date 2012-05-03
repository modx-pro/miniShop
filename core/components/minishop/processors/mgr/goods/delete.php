<?php
/**
 * Delete an Goods.
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];

$response = $modx->runProcessor('resource/delete', $scriptProperties);

if ($response->isError()) {
	return $modx->error->failure($response->getMessage());
}

$modx->removeCollection('ModGoods', array('gid' => $id));
$modx->removeCollection('ModCategories', array('gid' => $id));
$modx->removeCollection('ModTags', array('rid' => $id));

return $modx->error->success('',$res);
