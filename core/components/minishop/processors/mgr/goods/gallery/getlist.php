<?php
/**
 * miniShop
 *
 * Copyright 2010 by Shaun McCormick <shaun+minishop@modx.com>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
/**
 * Get a list of Imaged of product
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

//$goods_tpls = $miniShop->config['ms_goods_tpls'];
//$category_tpls = $miniShop->config['ms_category_tpls'];

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$gid = $modx->getOption('gid',$_REQUEST,0);

if (empty($gid)) {return $modx->error->failure($modx->lexicon('ms.gallery.err_nf'));}
/*
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
	if (!($miniShop instanceof miniShop)) return '';
}
*/

$c = $modx->newQuery('ModGallery');
$c->where(array('gid' => $gid,'wid' => $_SESSION['minishop']['warehouse']));

$count = $modx->getCount('ModGallery',$c);
$c->sortby($sort,$dir);
if ($isLimit) {$c->limit($limit,$start);}
$images = $modx->getCollection('ModGallery', $c);

$arr = array();
foreach ($images as $v) {
    $arr[]= $v->toArray();
}
return $this->outputArray($arr, $count);
