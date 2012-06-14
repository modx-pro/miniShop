<?php
/**
 * Loads the header for mgr pages.
 *
 * @package minishop
 * @subpackage controllers
 */
//$modx->regClientCSS($miniShop->config['cssUrl'].'mgr.css');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/minishop.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    miniShop.config = '.$modx->toJSON($miniShop->config).';
    miniShop.config.connector_url = "'.$miniShop->config['connectorUrl'].'";
    miniShop.config.connectors_url = "'.$miniShop->config['connectorsUrl'].'";
    miniShop.action = "'.(!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0).'";
    miniShop.config.warehouse = "'.$_SESSION['minishop']['warehouse'].'";
    miniShop.config.category = "'.$_SESSION['minishop']['category'].'";
    miniShop.config.status = "'.$_SESSION['minishop']['status'].'";
    miniShop.config.statuses = '.$miniShop->config['statuses'].';
});
</script>');

return '';