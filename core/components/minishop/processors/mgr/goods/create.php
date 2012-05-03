<?php
/**
 * Create an Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($_POST['pagetitle'])) {
	$modx->error->addField('pagetitle',$modx->lexicon('ms.required_field'));
}
//if (empty($_POST['parent'])) {
	//$modx->error->addField('parent',$modx->lexicon('ms.required_field'));
//}
if ($modx->error->hasError()) {
    return $modx->error->failure($modx->lexicon('ms.required_field'));
}

$response = $modx->runProcessor('resource/create', $_POST);
if ($response->isError()) {
    return $modx->error->failure($response->getMessage());
}

$id = $response->response['object']['id'];
$_POST['id'] = $id;

if ($modx->getCount('modResource', $id) > 0) {
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
		$wids[] = $_SESSION['minishop']['warehouse'];
	}

	foreach ($wids as $wid) {
		$res2 = $modx->newObject('ModGoods', array('wid' => $wid, 'gid' => $id));
		$res2->set('article', $_REQUEST['article']);
		$res2->set('price', $_REQUEST['price']);
		$res2->set('weight', $_REQUEST['weight']);
		$res2->set('img', $_REQUEST['img']);
		$res2->set('remains', $_REQUEST['remains']);
		$res2->set('add1', $_REQUEST['add1']);
		$res2->set('add2', $_REQUEST['add2']);
		$res2->set('add3', $_REQUEST['add3']);
		$res2->save();
		$res2->addTags($_REQUEST['tags']);
	}

}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res);
