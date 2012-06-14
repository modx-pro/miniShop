<?php
/**
 * Get a list of Kits
 *
 * @package minishop
 * @subpackage processors
 */

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$kits_tpls = $miniShop->config['ms_kits_tpls'];

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$query = $modx->getOption('query',$_REQUEST, 0);

$c = $modx->newQuery('modResource');
$c->where(array('template:IN' => $kits_tpls));

// Filtering by search query
if (!empty($query)) {
	$c->andCondition(array('pagetitle:LIKE' => '%'.$query.'%'));
}

$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);

if ($isLimit) {$c->limit($limit,$start);}
$kits = $modx->getCollection('modResource', $c);

$modx->lexicon->load('core:resource');

$arr = array();
foreach ($kits as $v) {
	$tmp = array(
		'id' => $v->get('id')
		,'pagetitle' => $v->get('pagetitle')
		,'parent' => $v->get('parent')
		,'published' => $v->get('published')
		,'deleted' => $v->get('deleted')
		,'hidemenu' => $v->get('hidemenu')
	);
	$tmp['url'] = $this->modx->makeUrl($v->get('id'), '', '', 'full');

	$tmp['menu'] = array(
		array('text' => $modx->lexicon('ms.goods.change'), 'handler' => 'this.editKit')
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
