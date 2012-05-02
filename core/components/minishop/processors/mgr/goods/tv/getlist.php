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
$limit = $modx->getOption('limit',$_REQUEST, 20);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid', $_REQUEST, 0);

if (empty($gid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

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
