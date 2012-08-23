<?php
/**
 * Get a list of Snippets or Chunks for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,$modx->getOption('default_per_page'));

$fields = explode(',', $modx->getOption('minishop.import_fields', '', 'pagetitle,longtitle,introtext,content,ms_price,ms_weight,ms_article,ms_img'));
sort($fields);
$count = count($fields);

$arr = array();
for ($i=$start; $i<=($limit+$start); $i++) {
	if (isset($fields[$i])) {
		$arr[] = array(
			'field' => $fields[$i]
		);
	}
}
return $this->outputArray($arr, $count);
