<?php
/**
 * Get a list of TVs
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$categories_tpls = explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1));

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties, round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$gid = $modx->getOption('gid', $scriptProperties, 0);

if (empty($gid)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($resource = $modx->getObject('modResource', $gid)) {
	$count = $modx->getCount('modTemplateVarTemplate', array('templateid' => $resource->get('template')));
	
	$c = $resource->xpdo->newQuery('modTemplateVar');
	$c->query['distinct'] = 'DISTINCT';
	$c->select($resource->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
	if ($resource->isNew()) {
		$c->select(array(
			'modTemplateVar.default_text AS value',
			'0 AS resourceId'
		));
	} else {
		$c->select(array(
			'IF(ISNULL(tvc.value),modTemplateVar.default_text,tvc.value) AS value',
			$resource->get('id').' AS resourceId'
		));
	}
	$c->innerJoin('modTemplateVarTemplate','tvtpl',array(
		'tvtpl.tmplvarid = modTemplateVar.id',
		'tvtpl.templateid' => $resource->get('template'),
	));
	if (!$resource->isNew()) {
		$c->leftJoin('modTemplateVarResource','tvc',array(
			'tvc.tmplvarid = modTemplateVar.id',
			'tvc.contentid' => $resource->get('id'),
		));
	}
	$c->sortby('tvtpl.rank,modTemplateVar.rank');
	$c->limit($limit, $start);
	
	$tmp = $resource->xpdo->getCollection('modTemplateVar', $c);

	$arr = array();
	foreach ($tmp as $v) {
		$tmp = $v->toArray();
		$tmp['intro'] = !empty($tmp['value']) ? substr(strip_tags($tmp['value']), 0, 100) : ''; 
		$arr[] = $tmp;
	}
	return $this->outputArray($arr, $count);
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

