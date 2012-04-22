<?php
// Определяем переменные для работы
$id = $modx->getOption('id', $scriptProperties, $modx->resource->id);
$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.msGallery.row');
$limit = $modx->getOption('limit', $scriptProperties, 0);
$offset = $modx->getOption('offset', $scriptProperties, 0);
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");
$totalVar = $modx->getOption('totalVar', $scriptProperties, 'total');
$sortby = $modx->getOption('sortby', $scriptProperties, 'id');
$sortdir = $modx->getOption('sortdir', $scriptProperties, 'ASC');
$onlyImg = isset($onlyImg) ? $onlyImg : 1;

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
  $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
  if (!($modx->miniShop instanceof miniShop)) return '';
}


if (!$modx->getCount('modResource', $id)) {return $modx->lexicon('ms.goods.err_nf');}

$arr = array();
$q = $modx->newQuery('ModGallery');
$q->andCondition(array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']), '', 0);
if ($onlyImg) {
	$extensions = explode(',',$modx->getOption('upload_images'));
	$tmp = array();
	foreach ($extensions as $v) {
		$q->orCondition(array('file:LIKE' => '%.'.$v), '', 1);
	}
}

$total = $modx->getCount('ModGallery', $q);
$modx->setPlaceholder($totalVar, $total);

$q->sortby($sortby,$sortdir);
if (!empty($limit)) {$q->limit($limit,$offset);}
$gallery = $modx->getCollection('ModGallery', $q);


$result = array();
foreach ($gallery as $v) {
	$v = $v->toArray();
	if (!$res = $modx->getChunk($tpl, $v)) {
		$res = '<pre>'.(print_r($v, true)).'</pre>';
	}
	$result[] = $res;
}

return implode($outputSeparator, $result);
