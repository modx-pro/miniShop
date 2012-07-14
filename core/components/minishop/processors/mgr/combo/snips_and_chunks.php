<?php
/**
 * Get a list of Snippets or Chunks for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));
$goods_tpls = explode(',', $this->modx->getOption('minishop.goods_tpl', '', 1));

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query', $scriptProperties, 0);
$mode = $modx->getOption('mode', $scriptProperties, 'snippets');
$addall = $scriptProperties['addall'] ? 1 : 0;

if ($mode == 'snippets') {$model = 'modSnippet';}
else {$model = 'modChunk';}

$c = $modx->newQuery($model);
$c->select('id,name');

// Фильтрация по строке поиска
if (!empty($query)) {
	$c->andCondition(array('name:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount($model,$c);
$c->sortby($sort,$dir);

if ($isLimit) $c->limit($limit,$start);

$res = $modx->getCollection($model,$c);
if ($addall && empty($query)) {
	$arr = array(array('id' => 0, 'name' => $modx->lexicon('ms.combo.all')));
}
else {
	$arr = array();
}
foreach ($res as $v) {
	$arr[]= $v->toArray();
}
return $this->outputArray($arr,$count);
