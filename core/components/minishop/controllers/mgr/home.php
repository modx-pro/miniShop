<?php
/**
 * Loads the home page.
 *
 * @package minishop
 * @subpackage controllers
 * @var modX $modx
 * @var miniShop $miniShop
 **/
 
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/util/datetime.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/kits.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/statuses.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/payments.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/warehouse.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/import-export.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/goods.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/orders.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/sections/home.js');
$modx->regClientCss($miniShop->config['cssUrl'].'mgr/main.css');
$output = '<div id="minishop-panel-home-div"></div>';

return $output;
