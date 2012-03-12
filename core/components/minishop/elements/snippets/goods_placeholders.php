<?php
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

$gid = $modx->resource->id;					// goods id
$wid = $_SESSION['minishop']['warehouse'];	// warehouse id

if ($res = $modx->getObject('ModGoods', array('gid' => $gid, 'wid' => $wid))) {
	$arr = $res->toArray();
	unset($arr['id']);

	$modx->setPlaceholders($arr);
}

if ($res = $modx->getObject('ModWarehouse', $wid)) {
	$arr = array(
		'warehouse' => $res->get('name')
		,'currency' => $res->get('currency')
	);
	
	$modx->setPlaceholders($arr);
}
return '';
?>