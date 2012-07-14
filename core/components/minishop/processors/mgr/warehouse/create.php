<?php
/**
 * Create an Warehouse
 * 
 * @package modextra
 * @subpackage processors
 */
if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModWarehouse',array('name' => $scriptProperties['name']))) {
    $modx->error->addField('name',$modx->lexicon('ms.warehouse.err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$res = $modx->newObject('ModWarehouse');
unset($scriptProperties['id']);
$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.warehouse.err_save'));
}

return $modx->error->success('',$res);