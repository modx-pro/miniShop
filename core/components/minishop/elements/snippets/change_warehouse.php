<?php
//include $modx->getOption('minishop.core_path') . 'elements/snippets/change_warehouse.php';

if ($_POST['action'] == 'changeWarehouse' && !empty($_POST['warehouse'])) {
  	$_SESSION['minishop'] = array();
	$_SESSION['minishop']['warehouse'] = $_POST['warehouse'];
  	unset($_SESSION['warehouse']['delivery']);
	header('Location: ' . $modx->makeUrl($modx->resource->id, '', '', 'full'));
  	return;
}
else {
  if (!is_object($modx->miniShop)) {
    $miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
    if (!($miniShop instanceof miniShop)) return '';
  }

  if ($res = $modx->getCollection('ModWarehouse')) {
    $options = '';
    foreach ($res as $v) {
      
      if ($_SESSION['minishop']['warehouse'] == $v->get('id')) {
      	$selected = 'selected';
	$modx->setPlaceholder('currency', $v->get('currency'));	// Плейсхолдер валюты склада
      }
      else {
      	$selected = '';
      }
      
      
      $options .= '<option value="'.$v->get('id').'" '.$selected.'>'.$v->get('name').'</option>';
    }
    echo $modx->getChunk('tpl.msChangeWarehouse', array('options' => $options));
  }
}
?>