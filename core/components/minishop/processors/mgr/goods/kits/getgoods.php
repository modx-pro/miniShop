<?php
/**
 * Get a list of Goods for Kit
 *
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$scriptProperties,'pagetitle');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
//$query = $modx->getOption('query',$scriptProperties, 0);
$resource = $modx->getOption('gid', $scriptProperties, 0);

if (empty($resource)) {return $modx->error->faulure($modx->lexicon('ms.goods.err_ns'));}

$c = $modx->newQuery('MsKit', array('rid' => $resource));
$c->leftJoin('modResource', 'modResource', array("modResource.id = MsKit.gid"));
// Filtering by search query
/*
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}
*/

$count = $modx->getCount('MsKit',$c);
$c->sortby($sort,$dir);

if ($isLimit) {$c->limit($limit,$start);}
$kits = $modx->getCollection('MsKit', $c);

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
	$tmp['url'] = $modx->makeUrl($tmp['gid'], '', '', 'full');
	$tmp['menu'] = array(
		array('text' => $modx->lexicon('ms.goods.goto_site_page'), 'handler' => 'this.goToGoodsSitePage')
		,array('text' => $modx->lexicon('ms.goods.goto_manager_page'), 'handler' => 'this.goToGoodsManagerPage')
		,'-'
		,array('text' => $modx->lexicon('delete'), 'handler' => 'this.removeItem')
	);

	$arr[] = array_merge($tmp, $res);
}
return $this->outputArray($arr, $count);
