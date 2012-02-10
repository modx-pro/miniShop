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
 * Get a list of Categories
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,5);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid', $_REQUEST, 0);
$query = $modx->getOption('query', $_REQUEST, 0);

if (empty($gid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

$c = $modx->newQuery('modResource');
$c->select('id,pagetitle');
$c->where(array('template:IN' => $categories_tpls, 'isfolder' => 1));

// Фильтрация по строке поиска
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);

if ($isLimit) $c->limit($limit,$start);
$c->select('id,pagetitle');

// Узнаем основную категорию товара
if ($tmp = $modx->getObject('modResource', $gid)) {
	$parent = $tmp->get('parent');
}

$res = $modx->getCollection('modResource',$c);
$arr = array();
foreach ($res as $v) {
	if ($v->get('id') == $parent) {continue;} // Выключаем основную категорию товара из списка
    $tmp = array(
		'cid' => $v->get('id')
		,'gid' => $gid
		,'name' => $v->get('pagetitle')
	);
	
	if ($tmp2 = $modx->getObject('ModCategories', array('cid' => $v->get('id'), 'gid' => $gid))) {
		$tmp['enabled'] = 1;
	}
	else {
		$tmp['enabled'] = 0;
	}
	
	$arr[]= $tmp;
}
return $this->outputArray($arr,$count);