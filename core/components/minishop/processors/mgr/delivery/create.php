<?php
/**
 * Create an Delivery
 * 
 * @package modextra
 * @subpackage processors
 */

if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModDelivery',array('name' => $scriptProperties['name'], 'wid' => $scriptProperties['wid']))) {
    $modx->error->addField('name',$modx->lexicon('ms.delivery.err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$res = $modx->newObject('ModDelivery');
$scriptProperties['enabled'] = $scriptProperties['enabled'] == 'true' || $scriptProperties['enabled'] == '1' ? 1 : 0;
$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouses.err_save'));
}

return $modx->error->success('',$res);
