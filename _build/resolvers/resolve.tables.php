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
 * Resolve creating db tables
 *
 * @package minishop
 * @subpackage build
 */
 
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/';
            $modx->addPackage('minishop',$modelPath, $modx->config['table_prefix'].'ms_');

            $manager = $modx->getManager();

            $manager->createObjectContainer('ModAddress');
            $manager->createObjectContainer('ModCategories');
            $manager->createObjectContainer('ModDelivery');
            $manager->createObjectContainer('ModGoods');
            $manager->createObjectContainer('ModLog');
            $manager->createObjectContainer('ModOrderedGoods');
            $manager->createObjectContainer('ModOrders');
            $manager->createObjectContainer('ModStatus');
            $manager->createObjectContainer('ModWarehouse');
			
			$tmp = $modx->newObject('ModWarehouse', array('name' => 'Основной', 'currency' => 'руб.', 'email' => $modx->getOption('emailsender')));
			$tmp->save();
			$tmp = $modx->newObject('ModStatus', array(
				'name' => 'Новый'
				,'email2user' => 1
				,'email2manager' => 1
				,'subject2user' => 'Вы сделали заказ №[[+num]]'
				,'subject2manager' => 'Новый заказ №[[+num]]'
				,'body2user' => 'tpl.msOrderEmail.user'
				,'body2manager' => 'tpl.msOrderEmail.manager'
			));
			$tmp->save();

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;