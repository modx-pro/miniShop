<?php
/**
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
$ms = $modx->getService('minishop', 'miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
if (!($ms instanceof miniShop)) return '';

// We have object resource - it is usual modResource object.
// Getting id of resource
$id = $resource->get('id');

// Getting properties of product
if (!$res = $modx->getObject('MsGood', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {return 0;}

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
