<?php
/**
 * Get a list of Ordered Goods
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST, round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$oid = $modx->getOption('oid',$_REQUEST, 0);

$query = $modx->getOption('query',$_REQUEST, 0);

$c = $modx->newQuery('ModOrderedGoods');
$c->where(array('oid' => $oid));
$count = $modx->getCount('ModOrderedGoods',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('ModOrderedGoods',$c);

$arr = array();
foreach ($orders as $v) {
    $tmp = $v->toArray();
	if ($res = $modx->getObject('modResource', $tmp['gid'])) {
		$tmp['name'] = $res->get('pagetitle');
	}
	if ($tmp2 = json_decode($tmp['data'], true)) {
		if (is_array($tmp2)){
			$tmp['data_view'] = '<ul>';
			foreach ($tmp2 as $k => $v2) {
				$tmp['data_view'] .= "<li>".$modx->lexicon('ms.'.$k)." &mdash; $v2</li>";
			}
			$tmp['data_view'] .= '</ul>';
		}
		else {$tmp['data_view'] = $tmp2;}
	}
	else {$tmp['data_view'] = '';}

	$arr[] = $tmp;
}
return $this->outputArray($arr, $count);
