<?php
/**
 * Create an Status
 * 
 * @package modextra
 * @subpackage processors
 */
if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

unset($scriptProperties['id']);
$data = $scriptProperties;

if ($modx->getObject('ModStatus',array('name' => $data['name']))) {
    $modx->error->addField('name',$modx->lexicon('ms.status.err_ae'));
}

// Проверка параметров отправки уведомлений
$data['email2user'] = !empty($data['email2user']) ? 1 : 0;
$data['email2manager'] = !empty($data['email2manager']) ? 1 : 0;

if (!$data['email2user']) {
	$data['subject2user'] = '';
	$data['body2user'] = 0;
}
else {
	if (empty($data['subject2user'])) {$modx->error->addField('subject2user',$modx->lexicon('ms.required_field'));}
	if (empty($data['body2user'])) {$modx->error->addField('body2user',$modx->lexicon('ms.required_field'));}
}

if (!$data['email2manager']) {
	$data['subject2manager'] = '';
	$data['body2manager'] = 0;
}
else {
	if (empty($data['subject2manager'])) {$modx->error->addField('subject2manager',$modx->lexicon('ms.required_field'));}
	if (empty($data['body2manager'])) {$modx->error->addField('body2manager',$modx->lexicon('ms.required_field'));}
}
////////////////////
if (empty($data['color'])) {$data['color'] = '000000';}

if ($modx->error->hasError()) {
    return $modx->error->failure();
} 

$res = $modx->newObject('ModStatus');
$res->fromArray($data);
if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.status.err_save'));
}

return $modx->error->success('',$res);
