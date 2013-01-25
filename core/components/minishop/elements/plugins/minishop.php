<?php
switch ($modx->event->name) {
	case 'OnWebPagePrerender':
		if (!$modx->user->isAuthenticated('mgr') || $modx->resource->contentType != 'text/html' || $modx->resource->template == 0 || !$tmp = $modx->getObject('modAction', array('namespace' => 'minishop', 'controller' => 'index'))) {
			return false;
		}
		
		$action = $tmp->get('id');
		$id = $modx->resource->id;
		$tpl = $modx->resource->template;
		$target = '_top';
		$goods_tpls = explode(',',$modx->getOption('minishop.goods_tpl'));
		
		$modx->lexicon->load('minishop:default');
		
		if (in_array($tpl, $goods_tpls)) {
			$add = '<br/><a href="'.$modx->config['manager_url'].'?a='.$action.'&act=edit&item='.$id.'" target="'.$target.'">'.$modx->lexicon('ms.menu.editproduct').'</a>';
		}
		else {$add = '<br/><a href="'.$modx->config['manager_url'].'?a='.$action.'&act=tab&item=1" target="'.$target.'">'.$modx->lexicon('ms.menu.component').'</a>';}

		$html = '
			<div id="msMenu" style="position:absolute;z-index:10000;left:0;top:0;padding:5px;">
				<a href="'.$modx->config['manager_url'].'index.php?a=30&id='.$id.'" target="'.$target.'">'.$modx->lexicon('ms.menu.editpage').'</a>
				'.$add.'
			</div>';
		$modx->resource->_output .= $html;
	break;

	case 'OnEmptyTrash':
		$params = $modx->event->params;
		$ids = $params['ids'];
		if (empty($ids)) {return;}

		$modx->addPackage('minishop',$modx->getOption('core_path').'components/minishop/model/');
		
		$modx->removeCollection('ModGoods', array('gid:IN' => $ids));
		$modx->removeCollection('ModCategories', array('gid:IN' => $ids));
		$modx->removeCollection('ModGallery', array('gid:IN' => $ids));
		$modx->removeCollection('ModTags', array('rid:IN' => $ids));
		$modx->removeCollection('ModKits', array('rid:IN' => $ids));
		$modx->removeCollection('ModKits', array('gid:IN' => $ids));
	break;

	case 'OnSiteRefresh':
		if ($modx->cacheManager->refresh(array('default/msgr' => array()))) {
			$modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('refresh_default').': msGetResources');
		}
	break;
}

?>