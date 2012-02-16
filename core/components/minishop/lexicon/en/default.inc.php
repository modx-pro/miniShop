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
 * Default Russian Lexicon Entries for miniShop
 *
 * @package minishop
 * @subpackage lexicon
 */
$_lang['minishop'] = 'miniShop';
$_lang['ms.menu_desc'] = 'A simple online store for MODX Revolution';
$_lang['ms.order'] = 'Order';
$_lang['ms.orders'] = 'Orders';
$_lang['ms.goods'] = 'Goods';
$_lang['ms.warehouse'] = 'Warehouse';
$_lang['ms.warehouses'] = 'Warehouses';
$_lang['ms.category'] = 'Category';
$_lang['ms.categories'] = 'Categories';
$_lang['ms.address'] = 'Address';
$_lang['ms.orderhistory'] = 'Hystory';
$_lang['ms.delivery'] = 'Delivery';
$_lang['ms.email'] = 'Email';
$_lang['ms.currency'] = 'Currency';
$_lang['ms.receiver'] = 'Receiver';
$_lang['ms.phone'] = 'Phone';
$_lang['ms.index'] = 'Index';
$_lang['ms.region'] = 'Region';
$_lang['ms.city'] = 'City';
$_lang['ms.metro'] = 'Metro';
$_lang['ms.street'] = 'Street';
$_lang['ms.building'] = 'Building';
$_lang['ms.room'] = 'Room';
$_lang['ms.description'] = 'Description';
$_lang['ms.main'] = 'Main';
$_lang['ms.price'] = 'Price';
$_lang['ms.name'] = 'Name';
$_lang['ms.article'] = 'Article';
$_lang['ms.remains'] = 'Remains';
$_lang['ms.img'] = 'Image';
$_lang['ms.iid'] = 'ItemID';
$_lang['ms.type'] = 'Type';
$_lang['ms.color'] = 'Color';
$_lang['ms.content'] = 'Content';
$_lang['ms.enabled'] = 'Enable';
$_lang['ms.operation'] = 'Operation';
$_lang['ms.properties'] = 'Properties';
$_lang['ms.permission'] = 'Permission';
$_lang['ms.required_field'] = 'This field is required.';
$_lang['ms.no_permission'] = 'You don\'t have permission for this action.';
$_lang['ms.permission.description'] = 'Required permission for edit this object.';

$_lang['ms.warehouse.select'] = 'Select warehouse';
$_lang['ms.warehouse.create'] = 'New warehouse';
$_lang['ms.warehouse.update'] = 'Edit warehouse';
$_lang['ms.warehouse.remove'] = 'Remove warehouse';
$_lang['ms.warehouse.remove_confirm'] = 'Are you sure to remove this <b>warehouse</b>? <span style="color: brown;"> This will also remove <b>all</b> remains on this warehouse and its delivery methods!</span>';
$_lang['ms.warehouse.err_save'] = 'Failed to save the warehouse.';
$_lang['ms.warehouse.err_remove'] = 'Failed to remove the warehouse.';
$_lang['ms.warehouse.err_ae'] = 'Warehouse with the same name already exists.';
$_lang['ms.warehouse.err_nf'] = 'Warehouse not found.';
$_lang['ms.warehouses.desc.currency'] = 'Any currency type. RUR or $, for example.';


$_lang['ms.delivery.create'] = 'New delivery';
$_lang['ms.delivery.remove'] = 'Remove delivery';
$_lang['ms.delivery.update'] = 'Edit delivery';
$_lang['ms.delivery.remove_confirm'] = 'Are you sure to remove this delivery?';
$_lang['ms.delivery.err_save'] = 'Failed to save the delivery.';
$_lang['ms.delivery.err_remove'] = 'Failed to remove the delivery.';
$_lang['ms.delivery.err_ae'] = 'Delivery with the same name already exists.';
$_lang['ms.delivery.err_nf'] = 'Delivery not found.';

$_lang['ms.status'] = 'Status';
$_lang['ms.statuses'] = 'Statuses';
$_lang['ms.statusname'] = 'Statusname';
$_lang['ms.status.create'] = 'New status';
$_lang['ms.status.update'] = 'Edit status';
$_lang['ms.status.remove'] = 'Remove status';
$_lang['ms.status.remove_confirm'] = 'Are you sure to remove this status?';
$_lang['ms.email2user'] = 'Email to user';
$_lang['ms.subject2user'] = 'Subject for email to user';
$_lang['ms.body2user'] = 'Chunk for email to user';
$_lang['ms.email2manager'] = 'Email to manager';
$_lang['ms.subject2manager'] = 'Subject for email to manager';
$_lang['ms.body2manager'] = 'Chunk for email to manager';
$_lang['ms.status.err_ae'] = 'This name already exists.';
$_lang['ms.status.err_save'] = 'Failed to save the status.';

$_lang['ms.num'] = 'Number';
$_lang['ms.id'] = 'ID';
$_lang['ms.uid'] = 'UserID';
$_lang['ms.oid'] = 'OrderID';
$_lang['ms.gid'] = 'GoodsID';
$_lang['ms.cid'] = 'CatID';
$_lang['ms.ip'] = 'IP';
$_lang['ms.timestamp'] = 'Timestamp';
$_lang['ms.username'] = 'Login';
$_lang['ms.fullname'] = 'Fullname';

$_lang['ms.sum'] = 'Sum';
$_lang['ms.created'] = 'Created';
$_lang['ms.updated'] = 'Updated';
$_lang['ms.comment'] = 'Comment';

$_lang['ms.orders.intro_msg'] = 'Orders management section';
$_lang['ms.goods.intro_msg'] = 'Goods management section';
$_lang['ms.warehouses.intro_msg'] = 'Warehouses management section';
$_lang['ms.status.intro_msg'] = 'Statuses management section';

$_lang['ms.orders.item_err_save'] = 'Order not found.';
$_lang['ms.orders.edit'] = 'Edit order';
$_lang['ms.orders.remove'] = 'Remove order';
$_lang['ms.orders.remove_confirm'] = 'Are you sure to remove this order?';
$_lang['ms.orders.goods_err_save'] = 'Goods not found.';
$_lang['ms.orders.search'] = 'Search order by number';
$_lang['ms.orders.filter_clear'] = 'Clear';
$_lang['ms.status.select'] = 'Select status';
$_lang['ms.category.select'] = 'Select category';
$_lang['ms.combo.all'] = 'All';
$_lang['ms.combo.select'] = 'Select';

$_lang['ms.window.editorder'] = 'Edit order';
$_lang['ms.window.editgoods'] = 'Edit goods';
$_lang['ms.order_err_nf'] = 'Order not found';

$_lang['ms.address.createnew'] = 'Create new address';

$_lang['ms.goods.price'] = 'Price';
$_lang['ms.goods.name'] = 'Name';
$_lang['ms.goods.num'] = 'Count';
$_lang['ms.goods.sum'] = 'Sum';
$_lang['ms.goods.create'] = 'New goods';
$_lang['ms.goods.change'] = 'Edit goods';
$_lang['ms.goods.goto_manager_page'] = 'Open goods page';
$_lang['ms.goods.goto_site_page'] = 'View goods on site';
$_lang['ms.goods.delete'] = 'Remove goods';
$_lang['ms.goods.delete_confirm'] = 'Are you sure to remove goods?<br/>This will set parameter "deleted" to 1.';
$_lang['ms.goods.err_delete'] = 'Failed to remove goods.';
$_lang['ms.goods.err_ns'] = 'Goods ID not set.';
$_lang['ms.goods.err_nf'] = 'Goods not found';
$_lang['ms.goods.err_wh_ns'] = 'Warehouse ID not set.';
$_lang['ms.goods.err_save'] = 'Failure to save goods.';
$_lang['ms.goods.wh_err_nf'] = 'Warehouse not found.';
$_lang['ms.goods.duplicate'] = 'Apply to all warehouses.';
$_lang['ms.goods.duplicate.desc'] = 'This will apply additional goods parameters (price, article etc.) to all warehouses.';
$_lang['ms.goods.add1'] = 'Additional 1';
$_lang['ms.goods.add2'] = 'Additional 2';
$_lang['ms.goods.add3'] = 'Additional 3';

$_lang['ms.log.old'] = 'Old value';
$_lang['ms.log.new'] = 'New value';

$_lang['ms.addToCart.success'] = 'The goods were successfully added to cart.';
$_lang['ms.addToCart.error'] = 'Error adding goods.';
$_lang['ms.remFromCart.success'] = 'Goods were removed.';
$_lang['ms.remFromCart.error'] = 'Failure to remove goods.';
$_lang['ms.Cart.empty'] = 'Your cart is empty.';

$_lang['ms.delivery.self'] = 'Self delivery';
$_lang['ms.delivery.err_save'] = 'Failure to save delivery.';
$_lang['ms.delivery.create'] = 'New delivery.';
$_lang['ms.delivery.err_ae'] = 'This name already exists.';

$_lang['ms.changeCartCount.success'] = 'Changes saved successfully.';
$_lang['ms.changeCartCount.error'] = 'Error when changing.';

$_lang['ms.saveDelivery.success'] = 'You choose the delivery.';

$_lang['ms.validate.address_select'] = 'You must select a saved address, or enter a new.';
$_lang['ms.validate.email'] = 'Your Email is required for ordering.';
$_lang['ms.validate.notempty'] = 'This field is required.';
$_lang['ms.validate.receiver'] = 'Fullname is required';
$_lang['ms.validate.index'] = 'Zip code must be 6 digits';
$_lang['ms.validate.phone'] = 'The phone number must consist of digits.';

$_lang['ms.captcha.error'] = 'Wrong captcha! Please try again.';
$_lang['ms.cart_hash.error'] = 'You have changed the contents of the cart during checkout. Please confirm the order.';

$_lang['ms.vieworder'] = 'Details';
$_lang['ms.chunk.select'] = 'Select chunk';
$_lang['ms.template.select'] = 'Select template';



