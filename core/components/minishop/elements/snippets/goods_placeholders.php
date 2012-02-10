<?php
//include $modx->getOption('minishop.core_path') . 'elements/snippets/orders_placeholders.php';

if (!is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

$modx->miniShop->initialize();

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

?>