<?php

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
  $modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
  if (!($modx->miniShop instanceof miniShop)) return '';
}

// We have object resource - it is usual modResource object.
// Getting id of resource
$id = $resource->get('id');

// Getting properties of product
if (!$res = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {return 0;}

// Retrieving price
$price = $res->get('price');

/*
Here you can write any rules for modification of price of the goods
But remember, this snippet will be overwritten on upgrading miniShop to new version.
So you need to rename this snippet and specify new name in system setting "minishop.getprice_snippet"
For example we can increase price of every red colored product:
	Getting all properties of a request, including array "data" with additional properties from frontend
	
	$request = $_REQUEST;
	if ($request['data']['color'] == 'red') {
		$price += 200;
	}
*/

// By default we simply returning price from modGoods
return $price;
