<?php
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

$wid = $_SESSION['minishop']['warehouse'];

if ($res = $modx->getObject('ModGoods', array('gid' => $input, 'wid' => $wid))) {
	if ($options == 'price') {
		$result = $modx->runSnippet('msGetPrice', array('price' => $res->get('price')));
	}
	else {
		$result = $res->get($options);
	}

	if (empty($result)) {return ' ';}
	else {return $result;}
}
else {return ' ';}
?>