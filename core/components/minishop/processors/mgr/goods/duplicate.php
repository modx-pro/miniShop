<?php
/**
 * Duplicate an Goods
 *
 * @package minishop
 * @subpackage processors
 */


if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

if (empty($scriptProperties['id'])) {
	$modx->error->failure('pagetitle',$modx->lexicon('ms.required_field'));
}

if ($res = $modx->getObject('modResource', $scriptProperties['id'])) {
	$name = $res->get('pagetitle') . ' copy';
	if (strstr($name, ' copy #') != false) {
		preg_match('/\#[\d]{1,3}/', $name, $tmp);
		$i = substr($tmp[0], 1);
		$name = preg_replace('/\scopy \#[\d]{1,3}/', '', $name);
	}
}

if (!isset($i)) {$i = 1;}
while (true) {
	$add = ' #' . $i;
	if ($modx->getCount('modResource', array('pagetitle' => $name.$add)) == 0) {
		$name .= $add;
		break;
	}
	$i++;
}

$response = $modx->runProcessor('resource/duplicate', array('id' => $scriptProperties['id'], 'name' => $name));
if ($response->isError()) {
    return $modx->error->failure($response->getMessage());
}

$id_old = $scriptProperties['id'];
$id_new = $response->response['object']['id'];

if ($res_old = $modx->getObject('MsGood', array('gid' => $id_old, 'wid' => $_SESSION['minishop']['warehouse']))) {
	$tmp = $res_old->toArray();
	$tags = $res_old->getTags();
	unset($tmp['id']);
	$tmp['gid'] = $id_new;

	$res_new = $modx->newObject('MsGood');
	$res_new->fromArray($tmp);
	if (!$res_new->save()) {
		return $modx->error->failure('ms.goods.err_save');
	}

	$res = $modx->getIterator('MsCategory', array('gid' => $id_old));
	foreach ($res as $v) {
		$cid = $v->get('cid');
		$tmp = $modx->newObject('MsCategory', array('gid' => $id_new, 'cid' => $cid));
		$tmp->save();
	}

	$res = $modx->getIterator('MsGallery', array('gid' => $id_old, 'wid' => $_SESSION['minishop']['warehouse']));
	foreach ($res as $v) {
		$tmp = $modx->newObject('MsGallery');
		$tmp->fromArray($v->toArray());
		$tmp->set('id', 0);
		$tmp->set('gid', $id_new);
		$tmp->save();
	}
	$res_new->addTags($tags);
}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}
return $modx->error->success('', $res_new);
