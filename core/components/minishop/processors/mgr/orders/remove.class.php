<?php
/**
 * Remove an Order.
 *
 * @package minishop
 * @subpackage processors
 */
//if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
//
//$id = $scriptProperties['id'];
//if (!$order = $modx->getObject('MsOrder', $id)) {
//	return $modx->error->failure($modx->lexicon('ms.orders.err_nf'));
//}
//if ($modx->getOption('minishop.enable_remains')) {
//	$order->releaseReserved();
//}
//$modx->invokeEvent('msOnBeforeOrderDelete', array('order' => $order));
//if ($order->remove()) {
//	$modx->removeCollection('MsOrderedGood', array('oid' => $id));
//	$modx->removeCollection('MsLog', array('iid' => $id, 'type' => 'status'));
//	$modx->invokeEvent('msOnOrderDelete', array());
//}
//else {
//	return $modx->error->failure($modx->lexicon('ms.orders.err_remove'));
//}
//
//return $modx->error->success('',$order);
class MsOrderRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'MsOrder';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modorders';
    // @todo : make sure arguments are passed to events
    public $beforeRemoveEvent = 'msOnBeforeOrderDelete';
    public $afterRemoveEvent = 'msOnOrderDelete';

    public function afterRemove() {
        $id = $this->getProperty('id');
        $this->modx->removeCollection('MsOrderedGood', array('oid' => $id));
        $this->modx->removeCollection('MsLog', array('iid' => $id, 'type' => 'status'));
    }
}
return 'MsOrderRemoveProcessor';
