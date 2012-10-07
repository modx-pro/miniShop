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
/*
$modx->removeCollection('MsGood', array('gid' => $id));
$modx->removeCollection('MsCategory', array('gid' => $id));
$modx->removeCollection('MsTag', array('rid' => $id));

$q = $modx->newQuery('MsKit',array('rid' => $id));
$q->orCondition(array('gid' => $id));
$modx->removeCollection('MsKit', $q);
*/
return $modx->error->success('',$res);
