<?php
/**
 * Get a list of Imaged of product
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

//$goods_tpls = $miniShop->config['ms_goods_tpls'];
//$category_tpls = $miniShop->config['ms_category_tpls'];

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST, round($modx->getOption('default_per_page') / 2));
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid',$_REQUEST,0);

if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
/*
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}
*/

$c = $modx->newQuery('ModGallery');
$c->where(array('gid' => $gid,'wid' => $_SESSION['minishop']['warehouse']));

$count = $modx->getCount('ModGallery',$c);
$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}
$images = $modx->getCollection('ModGallery', $c);

$arr = array();
foreach ($images as $v) {
    $arr[]= $v->toArray();
}
return $this->outputArray($arr, $count);
