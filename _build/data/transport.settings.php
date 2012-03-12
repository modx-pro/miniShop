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
 * Loads system settings into build
 *
 * @package minishop
 * @subpackage build
 */
$settings = array();

$settings[0]= $modx->newObject('modSystemSetting');
$settings[0]->fromArray(array(
    'key' => 'minishop.status_new',
    'value' => 1,
    'xtype' => 'numberfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[1]= $modx->newObject('modSystemSetting');
$settings[1]->fromArray(array(
    'key' => 'minishop.categories_tpl',
    'value' => 1,
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[2]= $modx->newObject('modSystemSetting');
$settings[2]->fromArray(array(
    'key' => 'minishop.goods_tpl',
    'value' => 1,
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[3]= $modx->newObject('modSystemSetting');
$settings[3]->fromArray(array(
    'key' => 'minishop.getprice_snippet',
    'value' => 'msGetPrice',
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

return $settings;