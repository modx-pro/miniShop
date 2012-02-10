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
 * Get an Goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */

$id = $modx->getOption('id',$_REQUEST, 0);
$wid = $modx->getOption('wid',$_REQUEST, $_SESSION['minishop']['warehouse']);

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($res = $modx->getObject('modResource', $id)) {
	$arr = $res->toArray();
	
	if ($res2 = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $wid))) {
		$tmp = $res2->toArray();
		unset($tmp['id']);
		
		$arr = array_merge($arr, $tmp);
	}
	else {
		$arr['wid'] = $wid;
	}

}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

/* output */
return $modx->error->success('', $arr);