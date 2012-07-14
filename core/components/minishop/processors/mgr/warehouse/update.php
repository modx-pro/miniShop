<?php
/**
 * Update an Warehouse
 * 
 * @package modextra
 * @subpackage processors
 */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModWarehouse',array('name' => $scriptProperties['name'], 'id:!=' => $scriptProperties['id'] ))) {
    $modx->error->addField('name',$modx->lexicon('ms.warehouse.err_ae'));
} 
if ($modx->error->hasError()) {
    return $modx->error->failure();
} 

if (!$res = $modx->getObject('ModWarehouse', $scriptProperties['id'])) {
    $modx->error->failure($modx->lexicon('ms.warehouse.err_nf'));
}

$permission = $res->get('permission');
if (!empty($permission) && !$modx->hasPermission($permission)) {
	return $modx->error->failure($modx->lexicon('ms.no_permission'));
}

$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouse.err_save'));
}

return $modx->error->success('',$res);