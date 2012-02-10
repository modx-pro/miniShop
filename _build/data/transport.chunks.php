<?php
/**
 * miniShop
 *
 * Copyright 2012 by Vasiliy Naumkin <bezumkin@yandex.ru>
 *
 * eventsCalendar2 is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * eventsCalendar2 is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * eventsCalendar2; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package eventscalendar2
 */
/**
 * Add chunks to build
 * 
 * @package eventscalendar2
 * @subpackage build
 */
$snippets = array();

$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'Content.category',
    'description' => 'Основной чанк для вывода категории товаров.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/Content.category.tpl'),
),'',true,true);

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 0,
    'name' => 'Content.goods',
    'description' => 'Основной чанк для вывода страницы товара.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/Content.goods.tpl'),
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msAddrForm',
    'description' => 'Форма заказа товара.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msAddrForm.tpl'),
),'',true,true);

$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msCart',
    'description' => 'Контейнер корзины с заголовком таблицы и включением формы заказа.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msCart.outer.tpl'),
),'',true,true);

$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msCart.row',
    'description' => 'Строка товара в корзине.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msCart.row.tpl'),
),'',true,true);

$chunks[5]= $modx->newObject('modChunk');
$chunks[5]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msChangeWarehouse',
    'description' => 'Оформление переключателя складов на страницах сайта.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msChangeWarehouse.tpl'),
),'',true,true);

$chunks[6]= $modx->newObject('modChunk');
$chunks[6]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msGoods.row',
    'description' => 'Чанк для вывода одного товара в категории. Используется getResources.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msGoods.row.tpl'),
),'',true,true);

$chunks[7]= $modx->newObject('modChunk');
$chunks[7]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.manager',
    'description' => 'Почтовое уведомление менеджеру о заказе. Используется в настройках статусов компонента.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.manager.tpl'),
),'',true,true);

$chunks[8]= $modx->newObject('modChunk');
$chunks[8]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.row',
    'description' => 'Строка заказанного товара для таблицы в почтовом уведомлении.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.row.tpl'),
),'',true,true);

$chunks[9]= $modx->newObject('modChunk');
$chunks[9]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.user',
    'description' => 'Почтовое уведомление покупателю о заказе. Используется в настройках статусов компонента.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.user.tpl'),
),'',true,true);

$chunks[10]= $modx->newObject('modChunk');
$chunks[10]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msSubmitOrder.success',
    'description' => 'Сообщение об успешном оформлении заказа.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msSubmitOrder.success.tpl'),
),'',true,true);

return $chunks;
