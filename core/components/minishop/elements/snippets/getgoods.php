<?php
//echo require $modx->getOption('core_path') . 'components/minishop/elements/snippets/getgoods.php';

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

	$ids = $modx->miniShop->getGoodsByCategories($categoryId);

	return implode(',', $ids);
  
?>