<?php
/**
 * Get a list of Categories
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties, ($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'pagetitle');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$gid = $modx->getOption('gid', $scriptProperties, 0);
$query = $modx->getOption('query', $scriptProperties, 0);

if (empty($gid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

$c = $modx->newQuery('modResource');
$c->where(array('template:IN' => $categories_tpls, 'isfolder' => 1));

// Filter by search query
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$c->select('id,pagetitle,parent');

// Get main category of product
if ($tmp = $modx->getObject('modResource', $gid)) {
	$parent = $tmp->get('parent');
}

$res = $modx->getCollection('modResource',$c);
$arr = array();
foreach ($res as $v) {
	
	if ($v->get('id') == $parent) {continue;} // Excluding main category
	
	// If it is nested category - adding name of parent
	if ($tmp2 = $modx->getObject('modResource', array('id' => $v->get('parent'), 'template:IN' => $categories_tpls))) {
		$pagetitle = $tmp2->get('pagetitle') . ' &rarr; ' . $v->get('pagetitle');
	}
	else {$pagetitle = $v->get('pagetitle');}
	
    $tmp = array(
		'id' => $v->get('id')
		,'gid' => $gid
		,'pagetitle' => $pagetitle
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