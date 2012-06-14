<?php
/**
 * Get a list of Goods templates
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));} 

if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}

$goods_tpls = $miniShop->config['ms_goods_tpls'];

$c = $modx->newQuery('modTemplate');
$c->where(array('modTemplate.id:IN' => $goods_tpls));
$c->select('modTemplate.id,modTemplate.templatename');

$count = $modx->getCount('modTemplate', $c);
$res = $modx->getCollection('modTemplate',$c);

$arr = array();
foreach ($res as $v) {
	$tmp = array(
		'id' => $v->get('id')
		,'name' => $v->get('templatename')
	);

    $arr[]= $tmp;
}
return $this->outputArray($arr, $count);
