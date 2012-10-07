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
 * @package minishop
 */
require_once dirname(__FILE__) . '/model/minishop/minishop.class.php';

class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'home'; }
}

abstract class miniShopManagerController extends modManagerController {
    /** @var miniShop $minishop */
    public $minishop;

    public function initialize() {
        $this->minishop = new miniShop($this->modx);

        //$this->addCss($this->cmpstarter->config['css_url'] . 'mgr.css');
        $this->addJavascript($this->minishop->config['js_url'] . 'mgr/minishop.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                miniShop.config = '. $this->modx->toJSON($this->minishop->config) .';
                miniShop.action = "'. (!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0) .'";

                miniShop.config.warehouse = "'.$_SESSION['minishop']['warehouse'].'";
                miniShop.config.category = "'.$_SESSION['minishop']['category'].'";
                miniShop.config.status = "'.$_SESSION['minishop']['status'].'";
                miniShop.config.statuses = "'.$this->minishop->config['statuses'].'";
            });
        </script>');
        parent::initialize();
    }

    public function getLanguageTopics() {
        return array('minishop:default');
    }

    public function checkPermissions() { return true; }
}
