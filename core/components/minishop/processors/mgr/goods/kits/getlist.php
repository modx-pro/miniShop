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

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,$modx->getOption('default_per_page'));
$sort = $modx->getOption('sort',$scriptProperties,'pagetitle');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties, 0);

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
	
	// Resources in kit
	$q = $modx->newQuery('ModKits', array('rid' => $v->get('id')));
	$q->select('gid');
	if ($q->prepare() && $q->stmt->execute()) {
		$ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		if (count($ids)) {
			$q = $modx->newQuery('modResource', array('id:IN' => $ids));
			$q->select('pagetitle');
			if ($q->prepare() && $q->stmt->execute()) {
				$titles = $q->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
				$tmp['resources'] = implode(', ', $titles);
			}
		}
	}
	
	//Menu
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
