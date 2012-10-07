<?php
/**
 * @var modX $modx
 * @var miniShop $ms
 * @var array $scriptProperties
 */
if ($_POST['action'] == 'changeWarehouse' && !empty($_POST['warehouse'])) {
    $_SESSION['minishop'] = array();
    $_SESSION['minishop']['warehouse'] = $_POST['warehouse'];
    unset($_SESSION['warehouse']['delivery']);
    header('Location: ' . $modx->makeUrl($modx->resource->id, '', '', 'full'));
    return;
}
else {
    $ms = $modx->getService('minishop','miniShop', $modx->getOption('minishop.core_path', null, $modx->getOption('core_path') . 'components/minishop/') . 'model/minishop/', $scriptProperties);
    if (!($ms instanceof miniShop)) return '';

    if ($res = $modx->getCollection('MsWarehouse')) {
        $options = array();
        /** @var MsWarehouse $v */
        foreach ($res as $v) {
            $selected = '';
            if ($_SESSION['minishop']['warehouse'] == $v->get('id')) {
                $selected = 'selected';
                $modx->setPlaceholder('currency', $v->get('currency'));	// Плейсхолдер валюты склада
            }
            $options[] = '<option value="'.$v->get('id').'" '.$selected.'>'.$v->get('name').'</option>';
        }
        return $modx->getChunk('tpl.msChangeWarehouse', array('options' => implode("\n", $options)));
    }
}
