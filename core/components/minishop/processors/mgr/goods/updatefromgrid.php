<?php
/**
 * Update an Order from grid
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);
$cid = $tmp['id'];
$gid = $tmp['gid'];
$enabled = $tmp['enabled'];

if (empty($gid) || empty($cid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($res = $modx->getObject('MsCategory', array('gid' => $gid, 'cid' => $cid))) {
	if (!$enabled) {
		$res->remove();
	}
}
else {
	if ($enabled) {
		$res = $modx->newObject('MsCategory', array('gid' => $gid, 'cid' => $cid));
		$res->save();
	}
}

/* output */
return $modx->error->success('', '');
