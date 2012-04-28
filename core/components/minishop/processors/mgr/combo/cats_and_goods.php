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
 * Get a list of Goods or Categories for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));
$goods_tpls = explode(',', $this->modx->getOption('minishop.goods_tpl', '', 1));

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,5);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$query = $modx->getOption('query', $_REQUEST, 0);
$mode = $modx->getOption('mode', $_REQUEST, 'category');
$addall = $_REQUEST['addall'] ? 1 : 0;

$c = $modx->newQuery('modResource');
$c->select('id,pagetitle,parent');

// Режим работы комбо: вывод категорий, или товаров?
if ($mode == 'category') {
	$c->where(array('template:IN' => $categories_tpls, 'isfolder' => 1));
}
else if ($mode == 'goods') {
	$c->where(array('template:IN' => $goods_tpls, 'isfolder' => 0));
}

// Фильтрация по строке поиска
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);

if ($isLimit) $c->limit($limit,$start);
$res = $modx->getCollection('modResource',$c);

// Предварительная обработка элементов
$tmp = array();
foreach ($res as $v) {
	$id = $v->get('id');
	$parent = $v->get('parent');
	// Если вложенная категория - добавляем имя родителя
	if ($mode == 'category' && $tmp2 = $modx->getObject('modResource', array('id' => $parent, 'template:IN' => $categories_tpls))) {
		$pagetitle = $tmp2->get('pagetitle') . ' - ' . $v->get('pagetitle');
	}
	else {$pagetitle = $v->get('pagetitle');}
	
	$tmp[$id] = $pagetitle;
}
// Сортировка массива по именам
asort($tmp);

// Добавляем пункт "Все", если нужно
if ($addall && empty($query) && empty($start)) {
	$arr = array(array('id' => 0, 'pagetitle' => $modx->lexicon('ms.combo.all')));
}
else {$arr = array();}

foreach ($tmp as $k => $v) {
	$arr[]= array('id' => $k,'pagetitle' => $v);
}

return $this->outputArray($arr,$count);
