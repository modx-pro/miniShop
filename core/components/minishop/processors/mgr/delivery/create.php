<?php
/**
 * Create an Delivery
 * 
 * @package modextra
 * @subpackage processors
 */

if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModDelivery',array('name' => $_POST['name'], 'wid' => $_POST['wid']))) {
    $modx->error->addField('name',$modx->lexicon('ms.delivery.err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$res = $modx->newObject('ModDelivery');
$_POST['enabled'] = $_POST['enabled'] == 'true' || $_POST['enabled'] == '1' ? 1 : 0;
$res->fromArray($_POST);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouses.err_save'));
}

return $modx->error->success('',$res);
