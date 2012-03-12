<?php
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
  $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $c);
  if (!($modx->miniShop instanceof miniShop)) return '';
}

// resource - Объект modResource товара.
$id = $resource->get('id');

// Дополнительные свойства товара
if (!$res = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {return 0;}

// Получение цены.
$price = $res->get('price');

// Здесь можно написать любые правила для измения цены товаров, скидок, и прочего.
// Но помните, что этот сниппет перезапишется при обновлении магазина, поэтому его лучше переименовать и обязательно указать
// в системном параметре minishop.getprice_snippet

return $price;