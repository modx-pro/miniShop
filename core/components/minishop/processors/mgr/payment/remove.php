<?php
/**
 * Remove an Warehouse.
 * 
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$id = $scriptProperties['id'];
if (!$res = $modx->getObject('ModPayment', $id)) {
	return $modx->error->failure($modx->lexicon('ms.payment.err_nf'));
}

if ($res->remove() == false) {
    return $modx->error->failure($modx->lexicon('ms.payment.err_remove'));
}
else {
	$res = $modx->getCollection('ModDelivery', array('payments:LIKE' => '%'.$id.'%'));
	foreach ($res as $v) {
		$tmp = $v->getPayments();
		$key = array_search($id, $tmp);
		unset($tmp[$key]);
		$tmp = array_values($tmp);
		$v->set('payments', json_encode($tmp));
		$v->save();
	}
}

return $modx->error->success('',$res);