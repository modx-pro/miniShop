<?php
/**
 * Create an Image record for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$file = trim($scriptProperties['file']);
$gid = $scriptProperties['gid'];

// Проверка обязательных полей
if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
if (empty($scriptProperties['file'])) {$modx->error->addField('file',$modx->lexicon('ms.required_field'));}
if ($modx->error->hasError()) {return $modx->error->failure();}

$res = $modx->newObject('ModGallery');
$res->fromArray(array(
	'gid' => $gid
	,'wid' => $_SESSION['minishop']['warehouse']
	,'file' => $file
	,'name' => $scriptProperties['name']
	,'description' => $scriptProperties['description']
));

$res->save();
return $modx->error->success('', $res);
