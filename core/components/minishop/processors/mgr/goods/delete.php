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
 * Delete an Goods.
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('modResource', $id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
/*
if (!$modx->hasPermission('ms_orders_remove')) {
	return $modx->error->failure($modx->lexicon('ms.no_permission'));
}
*/
$res->set('deleted', 1);
if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.goods.err_delete'));
}
else {
	if ($res2 = $modx->getObject('ModGoods', array('gid' => $id))) {
		$res2->remove();
	}
}

/* output */
return $modx->error->success('',$res);