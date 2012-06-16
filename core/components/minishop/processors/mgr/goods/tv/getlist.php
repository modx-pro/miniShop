<?php
/**
 * Get a list of TVs
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST, round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid', $_REQUEST, 0);

if (empty($gid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

$q = $modx->newQuery('modTemplateVarResource', array('contentid' => $gid));
$count = $modx->getCount('modTemplateVarResource', $q);

$q->limit($limit, $start);
$q->sortby($sort, $dir);

$res = $modx->getCollection('modTemplateVarResource', $q);
$arr = array();
foreach ($res as $v) {
	$tmp = $v->toArray();
	$tmp['intro'] = !empty($tmp['value']) ? substr(strip_tags($tmp['value']), 0, 100) : ''; 
	if ($tv = $modx->getObject('modTemplateVar', $tmp['tmplvarid'])) {
		$tmp = array_merge($tv->toArray(), $tmp);
	}
	$arr[] = $tmp;
}

return $this->outputArray($arr,$count);

/*
if ($res = $modx->getObject('modResource', $gid)) {
	$tmp = $res->getMany('modTemplateVar');
	$count = count($tmp);
	$arr = array();
	foreach ($tmp as $v) {
		$tmp = $v->toArray();
		$tmp['intro'] = !empty($tmp['value']) ? substr(strip_tags($tmp['value']), 0, 100) : ''; 
		$arr[] = $tmp;
	}
	return $this->outputArray($arr,$count);
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
*/
