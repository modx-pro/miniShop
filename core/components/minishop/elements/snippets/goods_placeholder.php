<?php
//include $modx->getOption('minishop.core_path') . 'elements/snippets/goods_placeholder.php';

if (!is_object($modx->miniShop)) {
  $miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
  if (!($miniShop instanceof miniShop)) return '';
}

$wid = $_SESSION['minishop']['warehouse'];


if ($res = $modx->getObject('ModGoods', array('gid' => $input, 'wid' => $wid))) {

	if ($options == 'price') {
		echo $modx->runSnippet('msGetPrice', array('price' => $res->get('price')));
	}
	else {
		echo $res->get($options);
	}
}
else {
	echo ' ';
}
?>