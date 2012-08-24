<?php
/**
 * Update an Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($scriptProperties['pagetitle'])) {
	$modx->error->addField('pagetitle',$modx->lexicon('ms.required_field').': pagetitle');
}
//if (empty($scriptProperties['parent'])) {
//	$modx->error->addField('parent',$modx->lexicon('ms.required_field').': parent');
//}
if ($modx->error->hasError()) {
	return $modx->error->failure();
}

$id = $modx->getOption('id', $scriptProperties, 0);
$wid = $modx->getOption('wid', $scriptProperties, 0);

if ($modx->getCount('modResource', $id) > 0) {
	// Updating resource
	$response = $modx->runProcessor('resource/update', $scriptProperties);
	if ($response->isError()) {
		return $modx->error->failure($response->getMessage());
	}

	//$miniShop = new miniShop($modx);

	// If resource is deleted - clean records in miniShop and exit
	if ($scriptProperties['deleted'] == 1) {
		$response = $modx->runProcessor('mgr/goods/delete', $scriptProperties, array('processors_path' => MODX_CORE_PATH.'components/minishop/processors/'));
		if ($response->isError()) {
			return $modx->error->failure($response->getMessage());
		}
		return $modx->error->success('', $response->response);
	}

	$wids = array();
	// If updating resource on all warehouses - get its ids
	if ($scriptProperties['duplicate']) {
		$tmp = $modx->getCollection('ModWarehouse');
		foreach ($tmp as $v) {
			$permission = $v->get('permission');
			if (!empty($permission) && !$modx->hasPermission($permission)) {
				continue;
			}
			else {
				$wids[] = $v->get('id');
			}
		}
	}
	else {
		$wids[] = $wid;
	}

	foreach ($wids as $wid) {
		$nolog = 0;
		if (!$res = $modx->getObject('ModGoods', array('wid' => $wid, 'gid' => $id))) {
			$res = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
			$nolog = 1;
		}
		$old =  $res->get('remains');
		$res->set('article', $scriptProperties['article']);
		$res->set('price', $scriptProperties['price']);
		$res->set('weight', $scriptProperties['weight']);
		$res->set('img', $scriptProperties['img']);
		$res->set('remains', $scriptProperties['remains']);
		$res->set('add1', $scriptProperties['add1']);
		$res->set('add2', $scriptProperties['add2']);
		$res->set('add3', $scriptProperties['add3']);
		
		$modx->invokeEvent('msOnBeforeProductUpdate', array('product' => $res));
		if ($res->save()) {
			$modx->invokeEvent('msOnProductUpdate', array('product' => $res));
			$res->addTags($scriptProperties['tags']);
		}
	}
	
	// Defence agaid duplicate main category of the product and additionals
	if ($tmp = $modx->getObject('ModCategories', array('gid' => $id, 'cid' => $scriptProperties['parent']))) {
		$tmp->remove();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

return $modx->error->success('', $res);
