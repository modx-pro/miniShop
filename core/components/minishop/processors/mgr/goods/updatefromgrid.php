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
 * Update an Order from grid
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);
$cid = $tmp['id'];
$gid = $tmp['gid'];
$enabled = $tmp['enabled'];

if (empty($gid) || empty($cid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($res = $modx->getObject('ModCategories', array('gid' => $gid, 'cid' => $cid))) {
	if (!$enabled) {
		$res->remove();
	}
}
else {
	if ($enabled) {
		$res = $modx->newObject('ModCategories', array('gid' => $gid, 'cid' => $cid));
		$res->save();
	}
}

/* output */
return $modx->error->success('', '');