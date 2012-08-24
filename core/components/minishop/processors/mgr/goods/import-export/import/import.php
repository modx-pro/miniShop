<?php

$config = !empty($scriptProperties['config']) ? $scriptProperties['config'] : $_SESSION['minishop']['import'];
if (empty($config) || !is_array($config)) {
	return $modx->error->failure($modx->lexicon('ms.import.err_config'));
}
if (empty($scriptProperties['file']) ||empty($scriptProperties['category']) ||empty($scriptProperties['mode'])) {
	return $modx->error->failure($modx->lexicon('ms.import.err_ns'));
}
$offset = intval($scriptProperties['offset']);
$file = MODX_BASE_PATH . $scriptProperties['file'];
$category = $scriptProperties['category'];
$mode = $scriptProperties['mode'];
$purge = $scriptProperties['purge'];
$template = $scriptProperties['template'];
$time = time();
$time_limit = ini_get('max_execution_time') - 5;
$tpls = explode(',', $modx->getOption('minishop.categories_tpl'));
$types = explode(',', $modx->getOption('minishop.import_fields', '', 'pagetitle,longtitle,introtext,content,ms_price,ms_weight,ms_article,ms_img,tag,gallery'));
$wid = $modx->getOption('wid', $scriptProperties, $_SESSION['minishop']['warehouse']);

if (($file = fopen($file, "r")) !== false) {
	// Cleaning up category
	if ($purge == 1) {
		$q = $modx->newQuery('modResource', array('parent' => $category/*, 'template:IN' => $tpls*/));
		$q->select('id');
		if ($q->prepare() && $q->stmt->execute()) {
			$ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN,0);
		}
		$modx->removeCollection('modResource', array('id:IN' => $ids));
		$modx->removeCollection('ModGoods', array('gid:IN' => $ids));
		$modx->removeCollection('ModCategories', array('gid:IN' => $ids));
		$modx->removeCollection('ModTags', array('rid:IN' => $ids));
		$modx->removeCollection('ModGallery', array('gid:IN' => $ids));

		$q = $modx->newQuery('ModKits',array('rid:IN' => $ids));
		$q->orCondition(array('gid:IN' => $ids));
		$modx->removeCollection('ModKits', $q);
	}
	
	// Importing
	$i = 0;
	while (($csv = fgetcsv($file, 0, ';')) !== false) {
		if ($offset > 0 && $i < $offset) {$i++; continue;}
		$modx->error->message = null;
		$modx->error->errors = array();
		
		$tvs = $gallery = $tags = array();
		$product = array('parent' => $category, 'template' => $template);
		$ms = array('wid' => $wid);
		foreach ($config as $k => $v) {
			if ($v == 'none') {continue;}
			else if (strpos($v, 'ms_') !== false) {$ms[substr($v,3)] = $csv[$k];}
			else if (strpos($v, 'tv_') !== false) {$tvs[substr($v,3)] = $csv[$k];}
			else if ($v == 'gallery') {$gallery[] = $csv[$k];}
			else if ($v == 'tag') {$tags[] = $csv[$k];}
			else {$product[$v] = $csv[$k];}
		}
		
		if ($res = $modx->getObject('modResource', array('parent' => $category, 'pagetitle' => $product['pagetitle']))) {
			if ($mode == 'update') {
				$id = $res->get('id');
				$newproduct = array_merge($res->toArray(), $product, $ms);
				$newproduct['tags'] = $tags;
				$response = $modx->runProcessor(
					'mgr/goods/update'
					,$newproduct
					,array('processors_path' => MODX_CORE_PATH.'components/minishop/processors/')
				);
				if ($response->isError()) {
					return $modx->error->failure('Error on row '.$i.': '.$response->getMessage());
				}
				$modx->removeCollection('ModGallery', array('gid' => $id));
			}
			else {
				$i++;
				continue;
			}
		}
		else {
			$res = $modx->newObject('modResource');
			$res->fromArray($product);
			$newproduct = array_merge($res->toArray(), $product, $ms);
			$newproduct['tags'] = $tags;
			$response = $modx->runProcessor(
				'mgr/goods/create'
				,$newproduct
				,array('processors_path' => MODX_CORE_PATH.'components/minishop/processors/')
			);

			if ($response->isError()) {
				return $modx->error->failure('Error on row '.$i.': '.$response->getMessage());
			}
			else {
				$id = $response->response['object']['id'];
				if (!$res = $modx->getObject('modResource', $id)) {
					return $modx->error->failure('Error on row '.$i.': '.print_r($response->response['object'],1));
				}
			}
		}

		foreach ($gallery as $v) {
			if (!empty($v)) {
				$tmp = pathinfo($v);
				//return $modx->error->failure('Error on row '.$i.': '.print_r($tmp,1));
				if (empty($tmp['extension'])) {
					$response = $modx->runProcessor(
						'mgr/goods/gallery/load'
						,array('gid' => $id, 'dir' => $wid, 'dir' => $v)
						,array('processors_path' => MODX_CORE_PATH.'components/minishop/processors/')
					);
				}
				else {
					$response = $modx->runProcessor(
						'mgr/goods/gallery/create'
						,array('gid' => $id, 'wid' => $wid, 'file' => $v)
						,array('processors_path' => MODX_CORE_PATH.'components/minishop/processors/')
					);
				}
			}
		}
		foreach ($tvs as $k => $v) {
			if (!empty($v)) {
				$res->setTVValue($k, $v);
			}
		}
		
		$i++;
		if ((time() - $time) >= $time_limit) {
			return $modx->error->success($i);
		}
	}
	return $modx->error->success('ok');
}
else {
	return $modx->error->failure($modx->lexicon('ms.import.wrong_file'));
}

?>