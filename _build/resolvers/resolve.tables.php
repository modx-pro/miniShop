<?php
/**
 * Resolve creating db tables
 *
 * @package minishop
 * @subpackage build
 */

if ($object->xpdo) {
	$modx =& $object->xpdo;
	$modelPath = $modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/';
	$modx->addPackage('minishop',$modelPath/*, $modx->config['table_prefix'].'ms_'*/);

	$manager = $modx->getManager();

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$manager->createObjectContainer('MsAddress');
			$manager->createObjectContainer('MsCategory');
			$manager->createObjectContainer('MsDelivery');
			$manager->createObjectContainer('MsGood');
			$manager->createObjectContainer('MsLog');
			$manager->createObjectContainer('MsOrderedGood');
			$manager->createObjectContainer('MsOrder');
			$manager->createObjectContainer('MsStatus');
			$manager->createObjectContainer('MsWarehouse');
			$manager->createObjectContainer('MsPayment');
			$manager->createObjectContainer('MsGallery');
			$manager->createObjectContainer('MsTag');
			$manager->createObjectContainer('MsKit');

			$exists = $modx->getCount('MsWarehouse');
			if ($exists == 0) {
				$tmp = $modx->newObject('MsWarehouse', array(
					'name' => 'Основной'
					,'currency' => 'руб.'
					,'email' => $modx->getOption('emailsender')
				));
				$tmp->save();
			}

			$exists = $modx->getCount('MsStatus');
			if ($exists == 0) {
				$tmp = $modx->newObject('MsStatus', array(
					'name' => 'Новый'
					,'color' => '000000'
					,'email2user' => 1
					,'email2manager' => 1
					,'subject2user' => 'Вы сделали заказ #[[+num]]'
					,'subject2manager' => 'Новый заказ #[[+num]]'
					,'body2user' => 'tpl.msOrderEmail.user'
					,'body2manager' => 'tpl.msOrderEmail.manager'
				));
				$tmp->save();
				$tmp = $modx->newObject('MsStatus', array(
					'name' => 'Оплачен'
					,'color' => '008000'
					,'email2user' => 1
					,'email2manager' => 1
					,'subject2user' => 'Вы оплатили заказ #[[+num]]!'
					,'subject2manager' => 'Заказ #[[+num]] оплачен!'
					,'body2user' => 'tpl.msOrderEmail.user'
					,'body2manager' => 'tpl.msOrderEmail.manager'
				));
				$tmp->save();
			}

			break;
		case xPDOTransport::ACTION_UPGRADE:
			$manager->createObjectContainer('MsPayment');
			$manager->createObjectContainer('MsGallery');
			$manager->createObjectContainer('MsTag');
			$manager->createObjectContainer('MsKit');

			$gtable = $modx->getTableName('MsGood');
			$ogtable = $modx->getTableName('MsOrderedGood');
			$dtable = $modx->getTableName('MsDelivery');
			$otable = $modx->getTableName('MsOrder');
			$stable = $modx->getTableName('MsStatus');
			$ltable = $modx->getTableName('MsLog');
			$galtable = $modx->getTableName('MsGallery');
			$atable = $modx->getTableName('MsAddress');

			$res = $modx->getCollection('MsStatus');
			foreach ($res as $v) {
				if ($tmp = $modx->getObject('modChunk', array('name' => $v->get('body2user')))) {
					$v->set('body2user', $tmp->get('id'));
				}
				if ($tmp = $modx->getObject('modChunk', array('name' => $v->get('body2manager')))) {
					$v->set('body2manager', $tmp->get('id'));
				}
				$v->save();
			}

			$sql = "ALTER TABLE {$gtable} ADD `add1` VARCHAR(255) NOT NULL, ADD `add2` VARCHAR(255) NOT NULL , ADD `add3` TEXT NOT NULL";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$gtable} ADD  `reserved` INT NOT NULL DEFAULT '0' AFTER `remains`";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$ogtable} ADD `data` TEXT NOT NULL";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$gtable} ADD `weight` FLOAT(10,3) NOT NULL DEFAULT '0.000' AFTER `price`;
				ALTER TABLE {$ogtable} ADD `weight` FLOAT(10,3) NOT NULL DEFAULT '0.000' AFTER `price`;";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$dtable} ADD `payments` VARCHAR(255) NOT NULL DEFAULT '[]', ADD INDEX (`payments`)";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$otable} ADD `payment` INT NOT NULL DEFAULT '0' AFTER `delivery`";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$stable} CHANGE `body2user` `body2user` INT(10) NULL DEFAULT '0';
				ALTER TABLE {$stable} CHANGE `body2manager` `body2manager` INT(10) NULL DEFAULT '0';";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$gtable} CHANGE `weight` `weight` FLOAT(10,3) NOT NULL DEFAULT '0.000';
				ALTER TABLE {$ogtable} CHANGE `weight` `weight` FLOAT(10,3) NOT NULL DEFAULT '0.000';";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$dtable} CHANGE `price` `add_price` FLOAT(10, 2) NOT NULL DEFAULT '0.00';
				ALTER TABLE {$dtable} ADD `price` FLOAT(10, 2) NOT NULL DEFAULT '0.00' AFTER `description`;";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$otable} ADD `weight` FLOAT(10, 3) NOT NULL DEFAULT '0.000' AFTER `sum`";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$ltable} CHANGE `iid` `oid` INT(11) NOT NULL DEFAULT '0';
				ALTER TABLE {$ltable} ADD `iid` INT NOT NULL AFTER `oid` DEFAULT '0';
				ALTER TABLE {$ltable} ADD `comment` TEXT NULL";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$galtable} ADD `fileorder` INT NOT NULL DEFAULT '0'";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			$sql = "ALTER TABLE {$atable} ADD `country` VARCHAR(100) NOT NULL AFTER `phone`";
			if ($stmt = $modx->prepare($sql)) {$stmt->execute();}

			break;
	}
}
return true;
