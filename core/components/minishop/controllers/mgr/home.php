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
 * Loads the home page.
 *
 * @package minishop
 * @subpackage controllers
 */
$modx->regClientStartupScript($tickets->config['manager_url'].'assets/modext/util/datetime.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/statuses.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/warehouse.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/goods.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/orders.grid.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/sections/home.js');
$output = '<div id="minishop-panel-home-div"></div>';

return $output;
