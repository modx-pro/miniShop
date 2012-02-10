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
 * Get a list of Delivery
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$wid = $modx->getOption('wid',$_REQUEST,0);

$query = $modx->getOption('query',$_REQUEST, 0);
$c = $modx->newQuery('ModDelivery');
$c->where(array('wid' => $wid));

$count = $modx->getCount('ModDelivery',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$delivery = $modx->getCollection('ModDelivery',$c);

$arr = array();
foreach ($delivery as $v) {
    $tmp = $v->toArray();
	//$tmp['fullname'] = $v->getFullName();
	//$tmp['warehousename'] = $v->getWarehouseName();
	//$tmp['statusname'] = $v->getStatusName();
	$arr[]= $tmp;
	
}
return $this->outputArray($arr, $count);