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
 * Get a list of Goods
 *
 * @package warehouse
 * @subpackage processors
 */
if (!is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$goods_tpls = $miniShop->config['ms_goods_tpls'];
//$category_tpls = $miniShop->config['ms_category_tpls'];

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$query = $modx->getOption('query',$_REQUEST, 0);

$warehouse = $modx->getOption('warehouse', $_REQUEST, $_SESSION['minishop']['warehouse']);
$category = $modx->getOption('category', $_REQUEST, $_SESSION['minishop']['category']);
$_SESSION['minishop']['warehouse'] = $warehouse;
$_SESSION['minishop']['category'] = $category;

$c = $modx->newQuery('modResource');
$c->where(array('deleted' => false));
// Фильтрация по категории
if (!empty($category)) {
	$c->andCondition(array('parent' => $category), '', 1);
	
	$ids = $modx->miniShop->getGoodsByCategories($category);
	if (!empty($ids)) {
		$c->orCondition(array('id:IN' => $ids), '', 1);
	}
}
else {
	$c->where(array('template:IN' => $goods_tpls, 'deleted' => false));
}

// Фильтрация по строке поиска
if (!empty($query)) {
	// Поиск по названию
	$c->where(array('pagetitle:LIKE' => '%'.$query.'%'));
	
	// И по артиклю
	$gids = array();
	$tq = $modx->newQuery('ModGoods', array('article:LIKE' => '%'.$query.'%', 'wid' => $warehouse));
	$tq->select('id,gid');
	if ($tres = $modx->getCollection('ModGoods', $tq)) {
		foreach ($tres as $tv) {
			$gids[] = $tv->get('gid');
		}
	}
	if (!empty($gids)) {
		$c->orCondition(array('id:IN' => $gids));
	}
}

$count = $modx->getCount('modResource',$c);

$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}

$c->select('id,pagetitle,parent');
$goods = $modx->getCollection('modResource', $c);

$arr = array();
foreach ($goods as $v) {
	$tmp = array(
		'id' => $v->get('id')
		,'pagetitle' => $v->get('pagetitle')
		,'parent' => $v->get('parent')
	);
    $tmp['url'] = $this->modx->makeUrl($v->get('id'), '', '', 'full');
	if ($tmp2 = $modx->getObject('ModGoods', array('gid' => $tmp['id'], 'wid' => $warehouse)) ) {
		$tmp2 = $tmp2->toArray();
		unset($tmp2['id'], $tmp2['gid']);
	}
	else {
		$tmp2 = array(
			'wid' => $warehouse
		);
	}
	
	$tmp = array_merge($tmp, $tmp2);

    $arr[]= $tmp;
}
return $this->outputArray($arr, $count);