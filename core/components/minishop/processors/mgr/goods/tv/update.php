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
 * Update an TV of goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

// Проверка обязательных полей
if (empty($_POST['resourceId'])) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($tv = $modx->getObject('modTemplateVar',$_POST['id'])) {
	$tv->setValue($_POST['resourceId'],$_POST['value']);
	$tv->save();
}
else {
	return $modx->error->failure($modx->lexicon('ms.tv.err_nf'));
}

return $modx->error->success('', $res);
