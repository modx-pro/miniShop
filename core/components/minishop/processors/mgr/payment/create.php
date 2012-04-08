<?php
/**
 * modExtra
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
 * Create an Payment
 * 
 * @package modextra
 * @subpackage processors
 */
if (!$modx->hasPermission('create')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}
 
if($modx->getObject('ModPayment',array('name' => $_POST['name']))) {
    $modx->error->addField('name',$modx->lexicon('ms.payement.err_ae'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$res = $modx->newObject('ModPayment');
unset($_POST['id']);
$res->fromArray($_POST);

if ($res->save() == false) {
    return $modx->error->failure($modx->lexicon('ms.payement.err_save'));
}

return $modx->error->success('',$res);