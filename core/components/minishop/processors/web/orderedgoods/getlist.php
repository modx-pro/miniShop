<?php
/**
 * Get a list of Ordered Goods
 *
 * @package minishop
 * @subpackage processors
 */
 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$oid = $modx->getOption('oid',$scriptProperties, 0);
$query = $modx->getOption('query',$scriptProperties, 0);

$c = $modx->newQuery('ModOrderedGoods');
$c->where(array('oid' => $oid));
$count = $modx->getCount('ModOrderedGoods',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit, $start);
$orders = $modx->getCollection('ModOrderedGoods',$c);

$arr = array();
foreach ($orders as $v) {
	if ($v->get('data') == '[]') {$data = '';}
	else {
		$tmp = json_decode($v->get('data'), true);
		$data = '<ul>';
		foreach ($tmp as $k2 => $v2) {
			$data .= "<li>".$modx->lexicon('ms.'.$k2)." &mdash; $v2</li>";
		}
		$data .= '</ul>';
	}
	$product = $v->getGoodsParams();
	$arr[] = array(
		'name' => $v->getGoodsName()
		,'article' = is_object($product) ? $product->get('article') : ''
		,'num' => $v->get('num')
		,'price' => $v->get('price')
		,'weight' => $v->get('weight')
		,'sum' => $v->get('sum')
		,'data' => $data
	);

}
return $this->outputArray($arr, $count);
