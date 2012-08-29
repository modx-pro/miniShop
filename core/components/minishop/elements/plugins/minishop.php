<?php
if ($modx->event->name == 'OnWebPagePrerender') {
	if (!$modx->user->isAuthenticated('mgr')) {return;}

	if ($tmp = $modx->getObject('modAction', array('namespace' => 'minishop', 'controller' => 'index'))) {
		$action = $tmp->get('id');
	}
	else {return;}

	$id = $modx->resource->id;
	$tpl = $modx->resource->template;
	$target = '_blank'; // or _top
	$goods_tpls = explode(',',$modx->getOption('minishop.goods_tpl'));
	
	$modx->lexicon->load('minishop:default');
	
	if (in_array($tpl, $goods_tpls)) {
		$add = '<br/><a href="/manager/?a='.$action.'&act=edit&item='.$id.'" target="'.$target.'">'.$modx->lexicon('ms.menu.editproduct').'</a>';
	}
	else {$add = '<br/><a href="/manager/?a='.$action.'&act=tab&item=1" target="'.$target.'">'.$modx->lexicon('ms.menu.component').'</a>';}


	$html = '
		<div id="msMenu" style="position:absolute;z-index:1000;left:0;top:0;padding:5px;">
			<a href="/manager/index.php?a=30&id='.$id.'" target="'.$target.'">'.$modx->lexicon('ms.menu.editpage').'</a>
			'.$add.'
		</div>';
	$modx->resource->_output .= $html;
}

if ($modx->event->name == 'OnEmptyTrash') {
	$params = $modx->event->params;
	$ids = $params['ids'];
	if (empty($ids)) {return;}

	$modx->addPackage('minishop',$modx->getOption('core_path').'components/minishop/model/');
	
	$modx->removeCollection('ModGoods', array('gid:IN' => $ids));
	$modx->removeCollection('ModCategories', array('gid:IN' => $ids));
	$modx->removeCollection('ModGallery', array('gid:IN' => $ids));
	$modx->removeCollection('ModTags', array('rid:IN' => $ids));

	$q = $modx->newQuery('ModKits',array('rid:IN' => $ids));
	$q->orCondition(array('gid:IN' => $ids));
	$modx->removeCollection('ModKits', $q);
}

?>