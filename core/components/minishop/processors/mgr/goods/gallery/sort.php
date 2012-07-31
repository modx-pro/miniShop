<?php

if (!$product = $modx->getObject('ModGoods', array('gid' => $scriptProperties['gid'], 'wid' => $_SESSION['minishop']['warehouse']))) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

if ($scriptProperties['new_order'] > $scriptProperties['old_order']) {$direction = 'down';}
else {$direction = 'up';}

$files = $product->getGallery('id');
$empty = $ordered = $arr = array();
foreach ($files as $v) {
	$id = $v->get('id');

	if ($id == $scriptProperties['id']) {
		$excluded = $v;
		continue;
	}
	
	$order = $v->get('fileorder');
	
	if (empty($order)) {
		$empty[] = $v;
	}
	else {
		$ordered[$order] = $v;
	}
}

if (empty($empty)) {
	$tmp = $ordered;
	$ordered = array();
	foreach ($tmp as $k => $v) {
		$ordered[$k - 1] = $v;
	}
}

$flag = 0;
$count = count($files) - 1;
for ($i = 0; $i <= $count; $i++) {

	if ($i == $scriptProperties['new_order'] && ($direction == 'up' || $i == 1 || $scriptProperties['old_order'] == 0)) {
		$arr[] = $excluded;
	}
	
	if (isset($ordered[$i]) && is_object($ordered[$i])) {
		$arr[] = $ordered[$i];
	}
	else {
		$tmp = array_shift($empty);
		if (is_object($tmp)) {
			$arr[] = $tmp;
		}
	}
	
	if ($i == $scriptProperties['new_order'] && $direction == 'down' && $i != 1 && $scriptProperties['old_order'] != 0) {
		$arr[] = $excluded;
	}
}


foreach ($arr as $k => $v) {
	$v->set('fileorder', $k);
	$v->save();
}


//return $modx->error->failure($direction);
return $modx->error->success('');