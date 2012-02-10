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
 * Remove an Warehouse.
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModWarehouse', $id)) {
	return $modx->error->failure($modx->lexicon('ms.warehouse.err_nf'));
}

$permission = $res->get('permission');
if (!empty($permission) && !$modx->hasPermission($permission)) {
	return $modx->error->failure($modx->lexicon('ms.no_permission'));
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouse.err_remove'));
}
else {
	// Удаляем все товары и доставки на складе
	$modx->removeCollection('ModDelivery', array('wid' => $id));
	$modx->removeCollection('ModGoods', array('wid' => $id));
}

/* output */
return $modx->error->success('',$res);