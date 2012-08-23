<?php
/**
 * Get a list of Import fields
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
setlocale(LC_ALL,'en_US.UTF-8');

$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 4));

if (empty($scriptProperties['file'])) {
	return $modx->error->failure($modx->lexicon('ms.import.select_file'));
}

$file = $scriptProperties['file'];
$types = explode(',', $modx->getOption('minishop.import_fields', '', 'pagetitle,longtitle,introtext,content,ms_price,ms_weight,ms_article,ms_img'));

$fields = array();
if (($tmp = fopen($file, "r")) !== false) {
	$fields = fgetcsv($tmp, 0, ';');
}

$count = count($fields);
if ($fields == false || empty($count)) {
	return $modx->error->failure($modx->lexicon('ms.import.wrong_file'));
}

$arr = array();
for ($i=$start; $i<($limit+$start); $i++) {
	if (isset($fields[$i])) {
		if (!empty($_SESSION['minishop']['import'][$i])) {$dst = $_SESSION['minishop']['import'][$i];}
		else if (isset($types[$i])) {$dst = $types[$i];}
		else {
			$tmp = rand(0, count($types) - 1);
			$dst = $types[$tmp];
		}
		
		$_SESSION['minishop']['import'][$i] = $dst;
		$arr[] = array(
			'index' => $i
			,'src' => $fields[$i]
			,'dst' => $dst
		);
	}
}
return $this->outputArray($arr, $count);