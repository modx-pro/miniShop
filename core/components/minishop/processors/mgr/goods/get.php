<?php
/**
 * Get an Goods
 * 
 * @package minishop
 * @subpackage processors
 */
/* get board */

$id = $modx->getOption('id',$scriptProperties, 0);
$wid = $modx->getOption('wid',$scriptProperties, $_SESSION['minishop']['warehouse']);

if (empty($id)) {
	return $modx->error->failure($modx->lexicon('ms.goods.err_ns'));
}

if ($res = $modx->getObject('modResource', $id)) {
	$arr = $res->toArray();
	
	if ($res2 = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $wid))) {
		$tmp = $res2->toArray();
		unset($tmp['id']);
		
		$arr = array_merge($arr, $tmp);
		$arr['tags'] = $res2->getTags(1);
	}
	else {
		$arr['wid'] = $wid;
	}

}
else {
	return $modx->error->failure($modx->lexicon('ms.goods.err_nf'));
}

/* output */
return $modx->error->success('', $arr);
