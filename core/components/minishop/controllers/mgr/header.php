<?php
/**
 * miniShop
 *
 * Copyright 2010 by Shaun McCormick <shaun+minishop@modx.com>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
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