<?php
//include $modx->getOption('minishop.core_path') . 'elements/snippets/getgoods.php';

if (!is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

	$ids = $modx->miniShop->getGoods($categoryId);
	$scriptProperties['resources'] = implode(',', $ids);

	echo $modx->runSnippet('getResources', $scriptProperties);
  
?>