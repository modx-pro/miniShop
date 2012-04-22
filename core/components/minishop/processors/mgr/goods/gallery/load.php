<?php
/**
 * miniShop
 *
 * Copyright 2010 by Shaun McCormick <shaun+minishop@modx.com>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
/**
 * Search in directory and create images records for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$dir = trim($_POST['dir']).'/';
$gid = $_POST['gid'];

// Проверка обязательных полей
if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
if (empty($_POST['dir'])) {$modx->error->addField('dir',$modx->lexicon('ms.required_field'));}
if ($modx->error->hasError()) {return $modx->error->failure();}

$extensions = explode(',',$modx->getOption('upload_images'));
$base_path = $modx->getOption('base_path');
$pl = array('{assets_path}','{base_path}');
$vl = array($modx->getOption('assets_path'), $base_path);
$dir = str_replace($pl,$vl,$dir);

if (strstr($dir, $base_path) == false) {$dir = $base_path.$dir;}

if (!file_exists($dir)) {return $modx->error->failure('Wrong directory: '.$dir);}

$files = scandir($dir);
foreach ($files as $v) {
	if (is_dir($v)) {continue;}
	preg_match('/\.(.*)$/i', $v, $tmp);
	if (!in_array(strtolower($tmp[1]), $extensions)) {continue;}
	if (!file_exists($dir.$v)) {continue;}
	
	$name = preg_replace('/\.(.*)$/i', '', $v);
	$file = str_replace(array($modx->getOption('base_path'), '//'), array('','/'), $dir.$v);
	
	if (preg_match('/^\//',$file)) {$file = substr($file,1);}
	
	if ($modx->getCount('ModGallery', array('gid' => $gid, 'file' => $file))) {continue;}
	
	$res = $modx->newObject('ModGallery');
	$res->fromArray(array(
		'gid' => $gid
		,'wid' => $_SESSION['minishop']['warehouse']
		,'name' => $name
		,'file' => $file
	));
	$res->save();
	//return $modx->error->failure(print_r($res->toArray(),1));
}
