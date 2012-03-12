<?php
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
  $miniShop = $modx->getService('miniShop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/', $scriptProperties);
  if (!($miniShop instanceof miniShop)) return '';
}

$options = '';

$q = $modx->newQuery('ModDelivery');
$q->where(array('enabled' => 1, 'wid' => $_SESSION['minishop']['warehouse']));
$q->sortby('id','ASC');

if ($res = $modx->getCollection('ModDelivery', $q)) {
  foreach ($res as $v) {
    if ($_POST['delivery'] == $v->get('id')) {$sel = 'selected';} else {$sel = '';}
	if (!empty($rowTpl)) {
		$options .= $modx->getChunk($rowTpl, $v->toArray());
	}
	else {
		$options .= '<option value="'.$v->get('id').'" title="'.$v->get('description').'" '.$sel.'>'.$v->get('name').' ('.$v->get('price').'руб.)</option>';
	}
  }
}

return $options;