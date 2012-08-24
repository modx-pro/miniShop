<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

$gid = $modx->resource->id;					// goods id
$wid = $_SESSION['minishop']['warehouse'];	// warehouse id
/** @var ModGoods $res */
if ($res = $modx->getObject('ModGoods', array('gid' => $gid, 'wid' => $wid))) {
	$arr = $res->toArray();
	$arr['price'] = $modx->miniShop->getPrice($gid);
	$arr['tags'] = implode(', ', $res->getTags());
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
