<?php
/**
 * Get a list of Import types for cobmobox
 *
 * @package minishop
 * @subpackage processors
 */
if (!$modx->hasPermission('view')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,round($modx->getOption('default_per_page') / 2));

$fields = explode(',', $modx->getOption('minishop.import_fields', '', 'pagetitle,longtitle,introtext,content,ms_price,ms_weight,ms_article,ms_img,tag,gallery'));
$count = count($fields);

$arr = array();
if ($start == 0) {
	$arr[] = array('field' => 'none');
}
$start--;

for ($i=$start; $i<($limit+$start); $i++) {
	if (isset($fields[$i])) {
		$arr[] = array(
			'field' => $fields[$i]
		);
	}
}
return $this->outputArray($arr, $count);
