<?php
/**
 * Create an Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($scriptProperties['pagetitle'])) {
	$modx->error->addField('pagetitle',$modx->lexicon('ms.required_field'));
}
//if (empty($scriptProperties['parent'])) {
	//$modx->error->addField('parent',$modx->lexicon('ms.required_field'));
//}
if ($modx->error->hasError()) {
    return $modx->error->failure($modx->lexicon('ms.required_field'));
}

$response = $modx->runProcessor('resource/create', $scriptProperties);
if ($response->isError()) {
    return $modx->error->failure($response->getMessage());
}

$id = $response->response['object']['id'];
$scriptProperties['id'] = $id;

if ($modx->getCount('modResource', $id) > 0) {
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
		$wids[] = $_SESSION['minishop']['warehouse'];
	}

	foreach ($wids as $wid) {
		$res = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
		$res->set('article', $scriptProperties['article']);
		$res->set('price', $scriptProperties['price']);
		$res->set('weight', $scriptProperties['weight']);
		$res->set('img', $scriptProperties['img']);
		$res->set('remains', $scriptProperties['remains']);
		$res->set('add1', $scriptProperties['add1']);
		$res->set('add2', $scriptProperties['add2']);
		$res->set('add3', $scriptProperties['add3']);
		$res->save();
		$res->addTags($scriptProperties['tags']);
	}

}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);
