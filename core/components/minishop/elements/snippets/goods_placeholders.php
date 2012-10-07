<?php
/**
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
$ms = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
if (!($ms instanceof miniShop)) return '';
// goods id
$gid = $modx->resource->id;
// warehouse id
$wid = $_SESSION['minishop']['warehouse'];
// Good placeholders
if ($res = $modx->getObject('MsGood', array('gid' => $gid, 'wid' => $wid))) {
    $arr = $res->toArray();
    $arr['price'] = $ms->getPrice($gid);
    $arr['tags'] = implode(', ', $res->getTags());
    $modx->setPlaceholders($arr);
}
// Warehouse placeholders
if ($res = $modx->getObject('MsWarehouse', $wid)) {
    $arr = array(
        'warehouse' => $res->get('name')
        ,'currency' => $res->get('currency')
    );
    $modx->setPlaceholders($arr);
}
return '';
