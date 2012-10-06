<?php
/**
 * Update a Payment
 *
 * @package minishop
 * @subpackage processors
 */
//if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
//
//if($modx->getObject('MsPayment',array('name' => $scriptProperties['name'], 'id:!=' => $scriptProperties['id'] ))) {
//    $modx->error->addField('name',$modx->lexicon('ms.payment.err_ae'));
//}
//if ($modx->error->hasError()) {
//    return $modx->error->failure();
//}
//
//if (!$res = $modx->getObject('MsPayment', $scriptProperties['id'])) {
//    $modx->error->failure($modx->lexicon('ms.payment.err_nf'));
//}
//
//$res->fromArray($scriptProperties);
//
//if ($res->save() == false) {
//    return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
//}
//
//return $modx->error->success('',$res);
class MsPaymentUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'MsPayment';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modpayment';
}
return 'MsPaymentUpdateProcessor';
