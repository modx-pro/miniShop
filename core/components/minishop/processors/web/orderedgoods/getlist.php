<?php
/**
 * Get a list of Ordered Goods
 *
 * @package minishop
 * @subpackage processors
 */
 
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
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
	if ($v->get(data) == '[]') {$data = '';}
	else {
		$tmp = json_decode($v->get('data'), true);
		$data = '<ul>';
		foreach ($tmp as $k2 => $v2) {
			$data .= "<li>".$modx->lexicon('ms.'.$k2)." &mdash; $v2</li>";
		}
		$data .= '</ul>';
	}
	$arr[] = array(
		'name' => $v->getGoodsName()
		,'num' => $v->get('num')
		,'price' => $v->get('price')
		,'sum' => $v->get('sum')
		,'data' => $data
	);

}
return $this->outputArray($arr, $count);
