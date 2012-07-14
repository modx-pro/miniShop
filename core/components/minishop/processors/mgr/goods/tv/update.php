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

$tv->set('value', $scriptProperties['value']);
$tv->save();

return $modx->error->success('', $res);
