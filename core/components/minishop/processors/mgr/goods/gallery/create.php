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
 * Create an Image record for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$file = trim($_POST['file']);
$gid = $_POST['gid'];

// Проверка обязательных полей
if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
if (empty($_POST['file'])) {$modx->error->addField('file',$modx->lexicon('ms.required_field'));}
if ($modx->error->hasError()) {return $modx->error->failure();}

$res = $modx->newObject('ModGallery');
$res->fromArray(array(
	'gid' => $gid
	,'wid' => $_SESSION['minishop']['warehouse']
	,'file' => $file
	,'name' => $_POST['name']
	,'description' => $_POST['description']
));

$res->save();
return $modx->error->success('', $res);
