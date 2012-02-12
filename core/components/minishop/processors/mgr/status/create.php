<?php
/**
 * miniShop
 *
 * Copyright 2010 by Shaun McCormick <shaun+modextra@modx.com>
 *
 * modExtra is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * modExtra is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * modExtra; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modextra
 */
/**
 * Create an Status
 * 
 * @package modextra
 * @subpackage processors
 */
if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

unset($_POST['id']);
$data = $_POST;

if ($modx->getObject('ModStatus',array('name' => $data['name']))) {
    $modx->error->addField('name',$modx->lexicon('ms.status.err_ae'));
}

// Проверка параметров отправки уведомлений
$data['email2user'] = !empty($data['email2user']) ? 1 : 0;
$data['email2manager'] = !empty($data['email2manager']) ? 1 : 0;

if (!$data['email2user']) {
	$data['subject2user'] = $data['body2user'] ='';
}
else {
	if (empty($data['subject2user'])) {$modx->error->addField('subject2user',$modx->lexicon('ms.required_field'));}
	if (empty($data['body2user'])) {$modx->error->addField('body2user',$modx->lexicon('ms.required_field'));}
}
if (!$data['email2manager']) {
	$data['subject2manager'] = $data['body2manager'] = '';
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