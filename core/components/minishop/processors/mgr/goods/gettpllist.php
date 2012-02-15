<?php
/**
 * Warehouse
 *
 * Copyright 2010 by Shaun McCormick <shaun+warehouse@modx.com>
 *
 * Warehouse is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Warehouse is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Warehouse; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package warehouse
 */
/**
 * Get a list of Goods templates
 *
 * @package warehouse
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));} 

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

$goods_tpls = $miniShop->config['ms_goods_tpls'];

$c = $modx->newQuery('modTemplate');
$c->where(array('modTemplate.id:IN' => $goods_tpls));
$c->select('modTemplate.id,modTemplate.templatename');

$count = $modx->getCount('modTemplate', $c);
$res = $modx->getCollection('modTemplate',$c);

$arr = array();
foreach ($res as $v) {
	$tmp = array(
		'id' => $v->get('id')
		,'name' => $v->get('templatename')
	);

    $arr[]= $tmp;
}
return $this->outputArray($arr, $count);
