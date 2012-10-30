<?php
/**
 * Add miniShop events for plugins to build
 * 
 * @package minishop
 * @subpackage build
 */
$events = array();

$events[0]= $modx->newObject('modEvent');
$events[0]->fromArray(array (
  'name' => 'msOnBeforeOrderCreate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[1]= $modx->newObject('modEvent');
$events[1]->fromArray(array (
  'name' => 'msOnOrderCreate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[2]= $modx->newObject('modEvent');
$events[2]->fromArray(array (
  'name' => 'msOnBeforeOrderUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[3]= $modx->newObject('modEvent');
$events[3]->fromArray(array (
  'name' => 'msOnOrderUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[4]= $modx->newObject('modEvent');
$events[4]->fromArray(array (
  'name' => 'msOnBeforeOrderChangeStatus',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[5]= $modx->newObject('modEvent');
$events[5]->fromArray(array (
  'name' => 'msOnOrderChangeStatus',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[6]= $modx->newObject('modEvent');
$events[6]->fromArray(array (
  'name' => 'msOnBeforeOrderDelete',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[7]= $modx->newObject('modEvent');
$events[7]->fromArray(array (
  'name' => 'msOnOrderDelete',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[8]= $modx->newObject('modEvent');
$events[8]->fromArray(array (
  'name' => 'msOnBeforeTVUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[9]= $modx->newObject('modEvent');
$events[9]->fromArray(array (
  'name' => 'msOnTVUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[10]= $modx->newObject('modEvent');
$events[10]->fromArray(array (
  'name' => 'msOnBeforeProductUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[11]= $modx->newObject('modEvent');
$events[11]->fromArray(array (
  'name' => 'msOnProductUpdate',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[12]= $modx->newObject('modEvent');
$events[12]->fromArray(array (
  'name' => 'msOnBeforeCartAdd',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[13]= $modx->newObject('modEvent');
$events[13]->fromArray(array (
  'name' => 'msOnCartAdd',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[14]= $modx->newObject('modEvent');
$events[14]->fromArray(array (
  'name' => 'msOnBeforeCartRemove',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[15]= $modx->newObject('modEvent');
$events[15]->fromArray(array (
  'name' => 'msOnCartRemove',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[16]= $modx->newObject('modEvent');
$events[16]->fromArray(array (
  'name' => 'msOnBeforeCartChange',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);

$events[17]= $modx->newObject('modEvent');
$events[17]->fromArray(array (
  'name' => 'msOnCartChange',
  'service' => 6,
  'groupname' => 'miniShop',
), '', true, true);


return $events;
