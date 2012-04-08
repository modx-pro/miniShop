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
 * Enable or disable an Payment for this Delivery
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);

if (empty($tmp['id']) || empty($tmp['delivery'])) {
	return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
}

if ($res = $modx->getObject('ModDelivery', $tmp['delivery']))  {
	$cur_payments = $res->getPayments();
	$key = array_search($tmp['id'], $cur_payments);
	if ($tmp['enabled']) {
		$cur_payments[] = intval($tmp['id']);
	}
	else {
		unset($cur_payments[$key]);
	}
	$payments = array_unique($cur_payments);
	$res->set('payments', json_encode(array_values($payments)));
	$res->save();
}
else {
	return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
}

return $modx->error->success('',$res->toArray());