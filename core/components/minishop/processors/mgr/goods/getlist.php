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
 * Get a list of Goods
 *
 * @package minishop
 * @subpackage processors
 */
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
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

$c->leftJoin('ModGoods', 'ModGoods', array(
	"ModGoods.gid = modResource.id",
	"ModGoods.wid = ".$_SESSION['minishop']['warehouse']
));

$c->where(array('modResource.deleted' => false, 'modResource.template:IN' => $goods_tpls, 'modResource.isfolder:=' => 0));

// Фильтрация по категории
if (!empty($category)) {
	$c->andCondition(array('parent' => $category), '', 1);
	
	$ids = $modx->miniShop->getGoodsByCategories($category);
	if (!empty($ids)) {
		$c->orCondition(array('id:IN' => $ids), '', 1);
	}
}

// Фильтрация по строке поиска
if (!empty($query)) {
	// Поиск по названию и артиклю
	$c->andCondition(array('modResource.pagetitle:LIKE' => '%'.$query.'%'), '', 2);
	$c->orCondition(array('ModGoods.article:LIKE' => '%'.$query.'%'), '', 2);
}

$count = $modx->getCount('modResource',$c);
if ($sort == 'id') {$sort = 'modResource.id';}
$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}
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
