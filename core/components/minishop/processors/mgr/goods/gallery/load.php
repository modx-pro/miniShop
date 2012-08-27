<?php
/**
 * Search in directory and create images records for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$dir = trim($scriptProperties['dir']).'/';
$gid = $scriptProperties['gid'];

// Проверка обязательных полей
if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
if (empty($scriptProperties['dir'])) {$modx->error->addField('dir',$modx->lexicon('ms.required_field'));}
if ($modx->error->hasError()) {return $modx->error->failure();}

$extensions = explode(',',$modx->getOption('upload_images'));
$base_path = $modx->getOption('base_path');
$pl = array('{assets_path}','{base_path}');
$vl = array($modx->getOption('assets_path'), $base_path);
$dir = str_replace($pl,$vl,$dir);

if (strstr($dir, $base_path) == false) {$dir = $base_path.$dir;}

if (!file_exists($dir)) {return $modx->error->failure('Wrong directory: '.$dir);}

$order = $modx->getCount('ModGallery', array('gid' => $gid, 'wid' => $_SESSION['minishop']['warehouse']));

$files = scandir($dir);
foreach ($files as $v) {
	if (is_dir($v)) {continue;}
	preg_match('/\.(.*)$/i', $v, $tmp);
	if (!in_array(strtolower($tmp[1]), $extensions)) {continue;}
	if (!file_exists($dir.$v)) {continue;}
	
	$name = preg_replace('/\.(.*)$/i', '', $v);
	$file = str_replace(array($modx->getOption('base_path'), '//'), array('','/'), $dir.$v);
	
	if (preg_match('/^\//',$file)) {$file = substr($file,1);}
	
	if ($modx->getCount('ModGallery', array('gid' => $gid, 'wid' => $_SESSION['minishop']['warehouse'], 'file' => $file))) {continue;}
	
	$res = $modx->newObject('ModGallery');
	$res->fromArray(array(
		'gid' => $gid
		,'wid' => $_SESSION['minishop']['warehouse']
		,'name' => $name
		,'file' => $file
		,'fileorder' => $order
	));
	$res->save();
	$order++;
	
	//return $modx->error->failure(print_r($res->toArray(),1));
}
