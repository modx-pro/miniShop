<?php
/**
 * Base snippet to build the required tables
 *
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
$ms = $modx->getService('minishop', 'miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
if (!($ms instanceof miniShop)) return '';

$m = $modx->getManager();

$tables = array('MsAddress', 'MsCategory', 'MsDelivery', 'MsGood', 'MsLog', 'MsOrderedGood', 'MsOrder', 'MsStatus', 'MsWarehouse', 'MsPayment', 'MsGallery', 'MsTag', 'MsKit');

$output = array();
foreach ($tables as $table) {
    $created = $m->createObjectContainer($table);
    $output[] = $created ? '<li>'.$table.' created</li>' : '<li>'.$table.' not created</li>';
}

return '<ul>'. implode("\n", $output) .'</ul>';
