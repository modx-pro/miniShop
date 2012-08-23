<?php
/**
 * Update an Import config from grid
 * 
 * @package minishop
 * @subpackage processors
 */

if (!$modx->hasPermission('save')) {return $modx->error->failure($modx->lexicon('ms.no_permission'));}

$tmp = $modx->fromJSON($scriptProperties['data']);
$index = $tmp['index'];
$dst = $tmp['dst'];

$_SESSION['minishop']['import'][$index] = $dst;

/* output */
return $modx->error->success('', '');
