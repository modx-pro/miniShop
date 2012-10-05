<?php
/**
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
$ms = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
if (!($ms instanceof miniShop)) return '';

$id = intval($input);
$result = '';

if ($options == 'price') {
    $result =  $ms->getPrice($id);
} else {
    $wid = $_SESSION['minishop']['warehouse'];
    if ($res = $modx->getObject('ModGoods', array('gid' => $id, 'wid' => $wid))) {
        if ($options == 'tags') {
            $result =  implode(', ', $res->getTags());
        } else {
            $result = $res->get($options);
        }
    }
}

return $result;
