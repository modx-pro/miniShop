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
$wid = $modx->getOption('wid', $scriptProperties, $_SESSION['minishop']['warehouse']);

// Проверка обязательных полей
if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
if (empty($file)) {$modx->error->addField('file',$modx->lexicon('ms.required_field'));}
if ($modx->error->hasError()) {return $modx->error->failure();}

$order = $modx->getCount('MsGallery', array('gid' => $gid, 'wid' => $_SESSION['minishop']['warehouse']));

$res = $modx->newObject('MsGallery');
$res->fromArray(array(
	'gid' => $gid
	,'wid' => $wid
	,'file' => $file
	,'name' => $scriptProperties['name']
	,'description' => $scriptProperties['description']
	,'fileorder' => $order
));

$res->save();
return $modx->error->success('', $res);
