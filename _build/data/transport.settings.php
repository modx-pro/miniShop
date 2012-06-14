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

$settings[4]= $modx->newObject('modSystemSetting');
$settings[4]->fromArray(array(
    'key' => 'minishop.status_paid',
    'value' => 2,
    'xtype' => 'numberfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[5]= $modx->newObject('modSystemSetting');
$settings[5]->fromArray(array(
    'key' => 'minishop.payment_shopid',
    'value' => '0',
    'xtype' => 'numberfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[6]= $modx->newObject('modSystemSetting');
$settings[6]->fromArray(array(
    'key' => 'minishop.payment_key',
    'value' => '000000',
    'xtype' => 'text-password',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[7]= $modx->newObject('modSystemSetting');
$settings[7]->fromArray(array(
    'key' => 'minishop.enable_remains',
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[8]= $modx->newObject('modSystemSetting');
$settings[8]->fromArray(array(
    'key' => 'minishop.status_final',
    'value' => '0',
    'xtype' => 'numberfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[9]= $modx->newObject('modSystemSetting');
$settings[9]->fromArray(array(
    'key' => 'minishop.status_cancel',
    'value' => '0',
    'xtype' => 'numberfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[10]= $modx->newObject('modSystemSetting');
$settings[10]->fromArray(array(
    'key' => 'minishop.getweight_snippet',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[11]= $modx->newObject('modSystemSetting');
$settings[11]->fromArray(array(
    'key' => 'minishop.kits_tpl',
    'value' => 1,
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

$settings[11]= $modx->newObject('modSystemSetting');
$settings[11]->fromArray(array(
    'key' => 'minishop.default_kits_dir',
    'value' => 0,
    'xtype' => 'textfield',
    'namespace' => 'minishop',
    'area' => 'settings',
),'',true,true);

return $settings;