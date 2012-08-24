<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
    $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}

$id = intval($input);

$result = '';

if ($options == 'price') {
	$result =  $modx->miniShop->getPrice($id);
}
else {
	$wid = $_SESSION['minishop']['warehouse'];
    /** @var ModGoods $res */
	if ($res = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $wid))) {
		if ($options == 'tags') {
			$result =  implode(', ', $res->getTags());
		}
		else {
			$result = $res->get($options);
		}
	}
}

if (empty($result)) {return ' ';}
else {return $result;}
