<?php
/**
 * Get a list of Ordered Goods
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties, round($modx->getOption('default_per_page') / 2));
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
    $tmp = $v->toArray();

	$tmp['name'] = $v->getGoodsName();
	$tmp['url'] = $modx->makeUrl($tmp['gid'],'','','full');
	if ($product = $v->getGoodsParams()) {
		$tmp['article'] = $product->get('article');
	} else {$tmp['article'] = '';}

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

//class ModOrderedGoodsGetListProcessor extends modObjectGetListProcessor {
//    public $classKey = 'ModOrderedGoods';
//    public $defaultSortField = 'id';
//    public $defaultSortDirection = 'ASC';
//    public $languageTopics = array('minishop:default');
//    public $objectType = 'minishop.modwarehouse';
//
//    public function prepareQueryBeforeCount(xPDOQuery $c) {
//        $orderID = $this->getProperty('oid');
//        if (!$orderID) {
//            // @todo: return error
//        }
//        $c->where(array('oid' => $orderID));
//        return $c;
//    }
//
//    public function prepareRow(ModOrderedGoods $object) {
//        $objectArray = $object->toArray();
//        $objectArray['name'] = $object->getGoodsName();
//        $objectArray['url'] = $this->modx->makeUrl($objectArray['gid'], '', '', 'full');
//        /** @var ModGoods $product */
//        $product = $object->getGoodsParams();
//        if ($product) {
//            $objectArray['article'] = $product->get('article');
//        } else {
//            $objectArray['article'] = '';
//        }
//
//        return $objectArray;
//    }
//}
//return 'ModOrderedGoodsGetListProcessor';
