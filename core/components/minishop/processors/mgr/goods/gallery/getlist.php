<?php
/**
 * Get a list of Imaged of product
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST, round($modx->getOption('default_per_page') / 3));
$sort = $modx->getOption('sort',$_REQUEST,'fileorder');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid',$_REQUEST,0);

if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}

$c = $modx->newQuery('MsGallery');
$c->where(array('gid' => $gid,'wid' => $_SESSION['minishop']['warehouse']));

$count = $modx->getCount('MsGallery',$c);
$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}
$images = $modx->getCollection('MsGallery', $c);

$arr = array();
foreach ($images as $v) {
    $arr[]= $v->toArray();
}
return $this->outputArray($arr, $count);
