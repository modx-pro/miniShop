<?php
/**
 * Update an Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($_POST['pagetitle'])) {
	$modx->error->addField('pagetitle',$modx->lexicon('ms.required_field'));
}
//if (empty($_POST['parent'])) {
//	$modx->error->addField('parent',$modx->lexicon('ms.required_field'));
//}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$id = $modx->getOption('id', $_REQUEST, 0);
$wid = $modx->getOption('wid', $_REQUEST, 0);

if ($modx->getCount('modResource', $id) > 0) {
	// Updating resource
	$response = $modx->runProcessor('resource/update', $_POST);
	if ($response->isError()) {
		return $modx->error->failure($response->getMessage());
	}

	$miniShop = new miniShop($modx);

	// If resource is deleted - clean records in miniShop and exit
	if ($_REQUEST['deleted'] == 1) {
		$response = $modx->runProcessor('mgr/goods/delete', $_REQUEST, array('processors_path' => $miniShop->config['processorsPath']));
		if ($response->isError()) {
			return $modx->error->failure($response->getMessage());
		}
		return $modx->error->success('', $response->response);
	}

	$wids = array();
	// If updating resource on all warehouses - get its ids
	if ($_REQUEST['duplicate']) {
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
		if (!$res2 = $modx->getObject('ModGoods', array('wid' => $wid, 'gid' => $id))) {
			$res2 = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
			$nolog = 1;
		}
		$old =  $res2->get('remains');
		$res2->set('article', $_REQUEST['article']);
		$res2->set('price', $_REQUEST['price']);
		$res2->set('weight', $_REQUEST['weight']);
		$res2->set('img', $_REQUEST['img']);
		$res2->set('remains', $_REQUEST['remains']);
		$res2->set('add1', $_REQUEST['add1']);
		$res2->set('add2', $_REQUEST['add2']);
		$res2->set('add3', $_REQUEST['add3']);
		
		if ($res2->save()) {
			$res2->addTags($_REQUEST['tags']);
			if (!$nolog) {
				//$miniShop->Log('goods', $res2->get('id'), 'remains', $old, $_REQUEST['remains']);
			}
		}
	}
	
	// Defence agaid duplicate main category of the product and additionals
	if ($tmp = $modx->getObject('ModCategories', array('gid' => $id, 'cid' => $_REQUEST['parent']))) {
		$tmp->remove();
	}
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

return $modx->error->success('', $res);
