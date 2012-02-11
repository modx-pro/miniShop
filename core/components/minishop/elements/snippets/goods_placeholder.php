<?php
//echo include $modx->getOption('minishop.core_path') . 'elements/snippets/goods_placeholder.php';

if (!is_object($modx->miniShop)) {
  $miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
  if (!($miniShop instanceof miniShop)) return '';
}

$wid = $_SESSION['minishop']['warehouse'];


if ($res = $modx->getObject('ModGoods', array('gid' => $input, 'wid' => $wid))) {

	if ($options == 'price') {
	  	$result = $modx->runSnippet('msGetPrice', array('price' => $res->get('price')));
	}
	else {
		$result = $res->get($options);
	}
  
  	if (empty($result)) {
	    	return ' ';
	}
	else {
	    	return $result;
	}
}
else {
	return ' ';
}
?>