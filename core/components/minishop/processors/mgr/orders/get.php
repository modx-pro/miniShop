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
 * Get an Order
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */

$id = $modx->getOption('id',$_REQUEST, 0);

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.order_err_ns'));
}

if ($res = $modx->getObject('ModOrders', $id)) {
	$arr = $res->toArray();
	
	$arr['created'] =  $res->get('created');
	$arr['fullname'] =  $res->getFullName();
	$arr['email'] =  $res->getEmail();
	$arr['delivery_name'] = $res->getDeliveryName();
	$arr['delivery_price'] = $res->getDeliveryPrice();
	if ($tmp =  $res->getAddress()) {
		foreach($tmp as $k => $v) {
			$arr['addr_'.$k] = $v;
		}
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.order_err_nf'));
}

/* output */
return $modx->error->success('', $arr);