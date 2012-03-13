<?php
/**
 * miniShop
 *
 * Copyright 2012 by Vasiliy Naumkin <bezumkin@yandex.ru>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
/**
 * Add snippets to build
 * 
 * @package minishop
 * @subpackage build
 */
$snippets = array();
//$properties = include $sources['build'].'properties/properties.minishop.php';
$properties = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'msChangeWarehouse',
    'description' => 'Snippet for switch active warehouse on frontend.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/change_warehouse.php'),
),'',true,true);
$snippets[0]->setProperties($properties);
/*
$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoods',
    'description' => 'Returns comma separated ids list of resources, that match to category. Must be used with getResources',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getgoods.php'),
),'',true,true);
$snippets[1]->setProperties($properties);
*/
$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 0,
    'name' => 'msGetPrice',
    'description' => 'Snippet for modification the goods price. Any rules for changing price will be here.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/get_price.php'),
),'',true,true);
$snippets[2]->setProperties($properties);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoodsPlaceholder',
    'description' => 'Output filter for extended properties of goods, such as price, article, image and remains',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/goods_placeholder.php'),
),'',true,true);
$snippets[3]->setProperties($properties);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoodsPlaceholders',
    'description' => 'Snippet for setPlaceholders() all goods placeholders on goods page.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/goods_placeholders.php'),
),'',true,true);
$snippets[4]->setProperties($properties);

$snippets[5]= $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 0,
    'name' => 'miniShop',
    'description' => 'miniShop main snippet. Has many features, by default - show shopping cart with selected goods.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/minishop.php'),
),'',true,true);
$snippets[5]->setProperties($properties);

$snippets[6]= $modx->newObject('modSnippet');
$snippets[6]->fromArray(array(
    'id' => 0,
    'name' => 'msGetOrdersPlaceholders',
    'description' => 'Snippet for showing various orders and goods placeholders in email notices. Should be called in email chunks.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/orders_placeholders.php'),
),'',true,true);
$snippets[6]->setProperties($properties);

$snippets[7]= $modx->newObject('modSnippet');
$snippets[7]->fromArray(array(
    'id' => 0,
    'name' => 'msGetResources',
    'description' => 'A modified getResources 1.4.2pl for miniShop. Support multicategory and extended goods properties (price,article etc.). Recommended instead getResources + msGetGoods + msGetGoodsPlaceholder.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/ms_getresources.php'),
),'',true,true);
$snippets[7]->setProperties($properties);

$snippets[8]= $modx->newObject('modSnippet');
$snippets[8]->fromArray(array(
    'id' => 0,
    'name' => 'hook_msSaveForm',
    'description' => 'FormIt hook for submitting orders',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/hook_saveform.php'),
),'',true,true);
$snippets[8]->setProperties($properties);

unset($properties);
return $snippets;