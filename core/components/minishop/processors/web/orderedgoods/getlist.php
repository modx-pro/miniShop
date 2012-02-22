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
 * Get a list of Ordered Goods
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$oid = $modx->getOption('oid',$_REQUEST, 0);

$query = $modx->getOption('query',$_REQUEST, 0);

$c = $modx->newQuery('ModOrderedGoods');
$c->where(array('oid' => $oid));
$count = $modx->getCount('ModOrderedGoods',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('ModOrderedGoods',$c);

$arr = array();
foreach ($orders as $v) {
	$arr[] = array(
		'name' => $v->getGoodsName()
		,'num' => $v->get('num')
		,'price' => $v->get('price')
		,'sum' => $v->get('sum')
	);
}
return $this->outputArray($arr, $count);