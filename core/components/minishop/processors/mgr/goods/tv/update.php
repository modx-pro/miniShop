<?php
/**
 * Update an TV of goods
 * 
 * @package minishop
 * @subpackage processors
 */

 if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($_POST['id'])) {
	return $modx->error->failure($modx->lexicon('tv_err_ns'));
}

if ($tv = $modx->getObject('modTemplateVarResource',$_POST['id'])) {
	$tv->set('value', $_POST['value']);
	$tv->save();
}
else {
	return $modx->error->failure($modx->lexicon('tv_err_nf'));
}

return $modx->error->success('', $res);
