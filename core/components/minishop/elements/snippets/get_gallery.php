<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
    $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
  if (!($modx->miniShop instanceof miniShop)) return '';
}

if (empty($id)) {$id = $modx->resource->id;}

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
/** @var Modgallery $v */
foreach ($gallery as $v) {
	$v = $v->toArray();
	if (!$res = $modx->getChunk($tpl, $v)) {
		$res = '<pre>'.(print_r($v, true)).'</pre>';
	}
	$result[] = $res;
}

return implode($outputSeparator, $result);
