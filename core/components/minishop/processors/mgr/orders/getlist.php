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
 * Get a list of Orders
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'created');

$dir = $modx->getOption('dir',$_REQUEST,'DESC');
$warehouse = $modx->getOption('warehouse', $_REQUEST, $_SESSION['minishop']['warehouse']);
$status = $modx->getOption('status', $_REQUEST, $_SESSION['minishop']['status']);
$_SESSION['minishop']['warehouse'] = $warehouse;
$_SESSION['minishop']['status'] = $status;

$query = $modx->getOption('query',$_REQUEST, 0);
$c = $modx->newQuery('ModOrders');

if (!empty($status)) {
	$c->andCondition(array('status' => $status));
}

if (!empty($query)) {
	$c->andCondition(array('num:LIKE' => '%'.$query.'%'));
}
if (!empty($warehouse)) {
	$c->andCondition(array('wid' => $warehouse));
}

$count = $modx->getCount('ModOrders',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('ModOrders',$c);

$arr = array();
foreach ($orders as $v) {
    $tmp = $v->toArray();
	$tmp['fullname'] = $v->getFullName();
	$tmp['warehousename'] = $v->getWarehouseName();
	$tmp['sum'] += $v->getDeliveryPrice();
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);