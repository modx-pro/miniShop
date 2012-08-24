<?php
/**
 * miniShop Connector
 *
 * @package minishop
 */
 
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/');
require_once $corePath.'model/minishop/minishop.class.php';
$modx->minishop = new miniShop($modx);

$modx->lexicon->load('minishop:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->minishop->config,$corePath.'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));