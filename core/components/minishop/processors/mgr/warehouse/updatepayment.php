<?php
/**
 * Enable or disable an Payment for this Delivery
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */
if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);

if (empty($tmp['id']) || empty($tmp['delivery'])) {
	return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
}

if ($res = $modx->getObject('ModDelivery', $tmp['delivery']))  {
	$cur_payments = $res->getPayments();
	$key = array_search($tmp['id'], $cur_payments);
	if ($tmp['enabled']) {
		$cur_payments[] = intval($tmp['id']);
	}
	else {
		unset($cur_payments[$key]);
	}
	$payments = array_unique($cur_payments);
	$res->set('payments', json_encode(array_values($payments)));
	$res->save();
}
else {
	return $modx->error->failure($modx->lexicon('ms.payment.err_save'));
}

return $modx->error->success('',$res->toArray());