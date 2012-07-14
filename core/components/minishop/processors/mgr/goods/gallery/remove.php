<?php

/**
 * Create an Image record for Goods
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('remove')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
$id = $_POST['id'];

// Проверка обязательных полей
if (empty($id)) {return $modx->error->failure($modx->lexicon('ms.gallery.item.err_nf'));}
if ($res = $modx->getObject('ModGallery', $id)) {
	$res->remove();
}
return $modx->error->success('', $res);
