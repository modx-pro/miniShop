<?php
/**
 * Remove a Payment.
 *
 * @package minishop
 * @subpackage processors
 */
//if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
//
//$id = $scriptProperties['id'];
//if (!$res = $modx->getObject('ModPayment', $id)) {
//	return $modx->error->failure($modx->lexicon('ms.payment.err_nf'));
//}
//
//if ($res->remove() == false) {
//    return $modx->error->failure($modx->lexicon('ms.payment.err_remove'));
//}
//else {
//	$res = $modx->getCollection('ModDelivery', array('payments:LIKE' => '%'.$id.'%'));
//	foreach ($res as $v) {
//		$tmp = $v->getPayments();
//		$key = array_search($id, $tmp);
//		unset($tmp[$key]);
//		$tmp = array_values($tmp);
//		$v->set('payments', json_encode($tmp));
//		$v->save();
//	}
//}
//
//return $modx->error->success('',$res);
class ModPaymentRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'ModPayment';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modpayment';

    public function afterRemove() {
        $id = $this->getProperty('id');
        $deliveries = $this->modx->getCollection('ModDelivery', array('payments' => $id));
        /** @var ModDelivery $delivery */
        foreach ($deliveries as $delivery) {
            $payments = $delivery->getPayments();
            $key = array_search($id, $payments);
            unset($payments[$key]);

            $payments = array_values($payments);
            $delivery->set('payments', $this->modx->toJSON($payments));
            $delivery->save();
        }

    }
}
return 'ModPaymentRemoveProcessor';
