<?php
/**
 * Get a list of Goods for Kit
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
//$query = $modx->getOption('query',$_REQUEST, 0);
$resource = $modx->getOption('gid', $_REQUEST, 0);

if (empty($resource)) {return $modx->error->faulure($modx->lexicon('ms.goods.err_ns'));}

$c = $modx->newQuery('ModKits', array('rid' => $resource));
$c->leftJoin('modResource', 'modResource', array("modResource.id = ModKits.gid"));
// Filtering by search query
/*
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}
*/

$count = $modx->getCount('ModKits',$c);
$c->sortby($sort,$dir);

if ($isLimit) {$c->limit($limit,$start);}
$kits = $modx->getCollection('ModKits', $c);

$arr = array();
foreach ($kits as $v) {
    $tmp = $v->toArray();
	$res = array();
	if ($tmp2 = $modx->getObject('modResource', $tmp['gid'])) {
		$res = $tmp2->toArray();
		unset($res['id']);
		if ($tmp3 = $modx->getObject('modResource', $res['parent'])) {
			$res['parent'] = $tmp3->get('pagetitle');
		}
	}
	$arr[] = array_merge($tmp, $res);
}
return $this->outputArray($arr, $count);
