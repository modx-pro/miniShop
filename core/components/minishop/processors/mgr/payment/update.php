<?php
/**
 * Update an Payment
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if($modx->getObject('ModPayment',array('name' => $scriptProperties['name'], 'id:!=' => $scriptProperties['id'] ))) {
    $modx->error->addField('name',$modx->lexicon('ms.payment.err_ae'));
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

if (!$res = $modx->getObject('ModPayment', $scriptProperties['id'])) {
    $modx->error->failure($modx->lexicon('ms.payment.err_nf'));
}

$res->fromArray($scriptProperties);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
}

return $modx->error->success('',$res);
