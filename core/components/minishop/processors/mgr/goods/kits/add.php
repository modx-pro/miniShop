<?php
/**
 * Add an Product to Kit.
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$rid = $scriptProperties['rid'];
$gid = $scriptProperties['gid'];

if (empty($rid) || empty($gid)) {return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));}

if ($modx->getCount('MsKit', array('rid' => $rid, 'gid' => $gid))) {return false;}
else {
	$res = $modx->newObject('MsKit', array('rid' => $rid, 'gid' => $gid));
	$res->save();
}

return $modx->error->success('',$res);



