<?php
/**
 * Get a list of Goods
 *
 * @package minishop
 * @subpackage processors
 */
 
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$goods_tpls = $miniShop->config['ms_goods_tpls'];

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$query = $modx->getOption('query',$_REQUEST, 0);

$warehouse = $modx->getOption('warehouse', $_REQUEST, $_SESSION['minishop']['warehouse']);
$category = $modx->getOption('category', $_REQUEST, $_SESSION['minishop']['category']);
$_SESSION['minishop']['warehouse'] = $warehouse;
$_SESSION['minishop']['category'] = $category;

$c = $modx->newQuery('modResource');

$c->leftJoin('ModGoods', 'ModGoods', array(
	"ModGoods.gid = modResource.id",
	"ModGoods.wid = ".$_SESSION['minishop']['warehouse']
));

$c->where(array('modResource.template:IN' => $goods_tpls, 'modResource.isfolder:=' => 0));

// Filtering by category
if (!empty($category)) {
	if ($tmp = $modx->getObject('modResource', $category)) {
		$categories = $modx->getChildIds($category, 10, array('context' => $tmp->get('context_key')));
		$categories[] = $category;
		$c->andCondition(array('parent:IN' => $categories), '', 1);
	}
	
	$ids = $modx->miniShop->getGoodsByCategories($category);
	if (!empty($ids)) {
		$c->orCondition(array('id:IN' => $ids), '', 1);
	}
}

// Filtering by search query
if (!empty($query)) {
	// Search by pagetitle or article
	$c->andCondition(array('modResource.pagetitle:LIKE' => '%'.$query.'%'), '', 2);
	$c->orCondition(array('ModGoods.article:LIKE' => '%'.$query.'%'), '', 2);
}

$count = $modx->getCount('modResource',$c);
if ($sort == 'id') {$sort = 'modResource.id';}
$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}
$goods = $modx->getCollection('modResource', $c);

$modx->lexicon->load('core:resource');

$arr = array();
foreach ($goods as $v) {
	$tmp = array(
		'id' => $v->get('id')
		,'pagetitle' => $v->get('pagetitle')
		,'parent' => $v->get('parent')
		,'published' => $v->get('published')
		,'deleted' => $v->get('deleted')
		,'hidemenu' => $v->get('hidemenu')
	);
	$tmp['url'] = $this->modx->makeUrl($v->get('id'), '', '', 'full');
	if ($tmp2 = $modx->getObject('ModGoods', array('gid' => $tmp['id'], 'wid' => $warehouse)) ) {
		$tmp2 = $tmp2->toArray();
		unset($tmp2['id'], $tmp2['gid']);
	}
	else {
		$tmp2 = array(
			'wid' => $warehouse
		);
	}
	
	$tmp = array_merge($tmp, $tmp2);

	$tmp['menu'] = array(
		array('text' => $modx->lexicon('ms.goods.change'), 'handler' => 'this.editGoods')
		,array('text' => $modx->lexicon('ms.duplicate'), 'handler' => 'this.duplicateGoods')
		,'-'
		,array('text' => $modx->lexicon('ms.goods.goto_site_page'), 'handler' => 'this.goToGoodsSitePage')
		,array('text' => $modx->lexicon('ms.goods.goto_manager_page'), 'handler' => 'this.goToGoodsManagerPage')
		,'-'
	);
	$tmp['menu'][] = $tmp['published'] ? array('text' => $modx->lexicon('resource_unpublish'), 'handler' => 'this.unpublishGoods') : array('text' => $modx->lexicon('resource_publish'), 'handler' => 'this.publishGoods');
	$tmp['menu'][] = $tmp['deleted'] ? array('text' => $modx->lexicon('resource_undelete'), 'handler' => 'this.undeleteGoods') : array('text' => $modx->lexicon('resource_delete'), 'handler' => 'this.deleteGoods');

    $arr[]= $tmp;
}
return $this->outputArray($arr, $count);
