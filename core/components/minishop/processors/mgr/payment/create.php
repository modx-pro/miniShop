<?php
/**
 * Create an Payment
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if($modx->getObject('ModPayment',array('name' => $scriptProperties['name']))) {
    $modx->error->addField('name',$modx->lexicon('ms.payement.err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$res = $modx->newObject('ModPayment');
unset($scriptProperties['id']);
$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.payement.err_save'));
}

return $modx->error->success('',$res);
