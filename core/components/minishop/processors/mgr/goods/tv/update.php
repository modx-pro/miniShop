<?php
/**
 * Update an TV of goods
 * 
 * @package minishop
 * @subpackage processors
 */

 if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($scriptProperties['id'])) {
	return $modx->error->failure($modx->lexicon('tv_err_ns'));
}

if (!$tv = $modx->getObject('modTemplateVarResource', array('tmplvarid' => $scriptProperties['id'], 'contentid' => $scriptProperties['resourceId']))) {
	$tv = $modx->newObject('modTemplateVarResource', array('tmplvarid' => $scriptProperties['id'], 'contentid' => $scriptProperties['resourceId']));
}

$modx->invokeEvent('msOnBeforeTVUpdate', array('tv' => $tv));

$tv->set('value', $scriptProperties['value']);
if ($tv->save()) {
	$modx->invokeEvent('msOnTVUpdate', array('tv' => $tv));
}

return $modx->error->success('', $res);
