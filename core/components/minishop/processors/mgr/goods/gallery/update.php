<?php
/**
 * Update an Image record for Goods
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$file = trim($scriptProperties['file']);
$id = $scriptProperties['id'];

if (empty($id)) {return $modx->error->failure($modx->lexicon('ms.gallery.item.err_nf'));}
if (empty($scriptProperties['file'])) {
	$modx->error->addField('file',$modx->lexicon('ms.required_field'));
}
if ($modx->error->hasError()) {
	return $modx->error->failure();
}

if ($res = $modx->getObject('MsGallery', $id)) {
	$res->set('file', $file);
	$res->set('name', $scriptProperties['name']);
	$res->set('description', $scriptProperties['description']);
	$res->save();
	return $modx->error->success('', $res);
}
else {
	return $modx->error->failure($modx->lexicon('ms.gallery.item.err_nf'));
}

