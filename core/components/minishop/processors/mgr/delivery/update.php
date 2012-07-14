<?php
/**
 * Update an Warehouse
 * 
 * @package modextra
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModDelivery',array('name' => $scriptProperties['name'], 'wid' => $scriptProperties['wid'], 'id:!=' => $scriptProperties['id'] ))) {
    $modx->error->addField('name',$modx->lexicon('ms.delivery.err_ae'));
} 
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

if (!$res = $modx->getObject('ModDelivery', $scriptProperties['id'])) {
    $modx->error->failure($modx->lexicon('ms.delivery.err_nf'));
}

$scriptProperties['enabled'] = $scriptProperties['enabled'] == 'true' || $scriptProperties['enabled'] == '1' ? 1 : 0;
$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.delivery.err_save'));
}

return $modx->error->success('',$res);
