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
 * Update an Image record for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$file = trim($_POST['file']);
$id = $_POST['id'];

// Проверка обязательных полей
if (empty($id)) {return $modx->error->failure($modx->lexicon('ms.gallery.item.err_nf'));}
if (empty($_POST['file'])) {
	$modx->error->addField('file',$modx->lexicon('ms.required_field'));
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

if ($res = $modx->getObject('ModGallery', $id)) {
	$res->set('file', $file);
	$res->set('name', $_POST['name']);
	$res->set('description', $_POST['description']);
	$res->save();
	return $modx->error->success('', $res);
}
else {
	return $modx->error->failure($modx->lexicon('ms.gallery.item.err_nf'));
}

