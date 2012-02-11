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
    'description' => 'Сниппет для переключения активного склада на страницах сайта.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/change_warehouse.php'),
),'',true,true);
$snippets[0]->setProperties($properties);

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoods',
    'description' => 'Сниппет для выборки ресурсов по категориям. Использует getResources для вывода результатов, понимает его параметры и работает с getPage. ',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getgoods.php'),
),'',true,true);
$snippets[1]->setProperties($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 0,
    'name' => 'msGetPrice',
    'description' => 'Сниппет для вычисления окончательной цены товара. Может использоваться как Output filter, принимает цену и параметры. В него вы можете написать любые правила для скидок или надбавок.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getprice.php'),
),'',true,true);
$snippets[2]->setProperties($properties);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoodsPlaceholder',
    'description' => 'Фильтр вывода для получения параметров товаров, таких как цена, остаток на складе, изображение или артикул.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/goods_placeholder.php'),
),'',true,true);
$snippets[3]->setProperties($properties);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 0,
    'name' => 'msGetGoodsPlaceholders',
    'description' => 'Сниппет, который выводит на страницу все дополнительные свойства товаров через setPlaceholders(). Нужно использовать на странице товара.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/goods_placeholders.php'),
),'',true,true);
$snippets[4]->setProperties($properties);

$snippets[5]= $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 0,
    'name' => 'miniShop',
    'description' => 'Основной сниппет магазина. Обладает множеством функций. По умолчанию - выводит содержимое корзины.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/minishop.php'),
),'',true,true);
$snippets[5]->setProperties($properties);

$snippets[6]= $modx->newObject('modSnippet');
$snippets[6]->fromArray(array(
    'id' => 0,
    'name' => 'msGetOrdersPlaceholders',
    'description' => 'Сниппет для почтовых уведомлений. Выводит в письмо все плейсхолдеры заказа склада, адреса доставки и таблицу заказанных товаров.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/orders_placeholders.php'),
),'',true,true);
$snippets[6]->setProperties($properties);

$snippets[7]= $modx->newObject('modSnippet');
$snippets[7]->fromArray(array(
    'id' => 0,
    'name' => 'msGetResources',
    'description' => 'Модифицированный getResources 1.4.2pl для miniShop. Поддержка мультикатегорий и дополнительных свойств товаров.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/ms_getresources.php'),
),'',true,true);
$snippets[7]->setProperties($properties);

unset($properties);
return $snippets;