<?php
/**
 * Delete an OrderedGoods.
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModOrderedGoods', $id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
$oldval = $res->get('num');
$gid = $res->get('gid');
$oid = $res->get('gid');

if ($tmp = $modx->getObject('modResource', $gid)) {
	$name = $tmp->get('pagetitle');
}
else {
	$name = 'Unknown/deleted';
}

// Loading miniShop class
$miniShop = new miniShop($modx);

$oid = $res->get('oid');
$res->remove();
$miniShop->Log('goods', $oid, $gid, 'remove', $oldval, 0, 'Removed "'. $name . '" product from the order.');

if ($order = $modx->getObject('ModOrders', $oid)) {
	$order->updateSum();
	$order->updateWeight();
}

return $modx->error->success('',$res);

//class ModOrderedGoodsRemoveProcessor extends modObjectRemoveProcessor {
//    public $classKey = 'ModOrderedGoods';
//    public $languageTopics = array('minishop:default');
//    public $objectType = 'minishop.modorderedgoods';
//
//    public function afterRemove() {
//        $id = $this->getProperty('id');
//        $this->modx->removeCollection('ModDelivery', array('wid' => $id));
//        //$this->modx->removeCollection('ModGoods', array('wid' => $id));
//    }
//}
//return 'ModOrderedGoodsRemoveProcessor';
