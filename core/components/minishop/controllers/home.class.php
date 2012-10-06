<?php
class miniShopHomeManagerController extends miniShopManagerController {
    public function process(array $scriptProperties = array()) {}

    public function getPageTitle() { return $this->modx->lexicon('minishop'); }

    public function loadCustomCssJs() {
        // MODX related
        $this->addJavascript($this->modx->config['manager_url'].'assets/modext/util/datetime.js');

        // miniShop
        $this->addCss($this->minishop->config['css_url'].'mgr/main.css');

        $this->addJavascript($this->minishop->config['js_url'].'mgr/core/combos.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/core/utils.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/core/windows.js');

        $this->addJavascript($this->minishop->config['js_url'].'mgr/plugins/dragdropgrid.js');

        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/kits.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/statuses.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/payments.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/warehouse.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/import-export.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/goods.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/orders.grid.js');
        $this->addJavascript($this->minishop->config['js_url'].'mgr/widgets/home.panel.js');

        $this->addJavascript($this->minishop->config['js_url'].'mgr/sections/home.page.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.load({ xtype: "minishop-page-home"});

                var action = Ext.getUrlParam("act");
                var item = Ext.getUrlParam("item");
                var wid = Ext.getUrlParam("wid") || 1;

                if (action == "create") {
                    Ext.getCmp("minishop-tabs-main").setActiveTab("minishop-tabs-goods");
                    Ext.getCmp("minishop-tabs-goods-inner").setActiveTab("minishop-tabs-goods-inner-goods");
                    Ext.getCmp("minishop-grid-goods").createGoods("");
                } else if (action == "edit" && typeof item != "undefined") {
                    var row = {
                        data: {
                            id: item
                            ,wid: wid
                        }
                    };
                    Ext.getCmp("minishop-tabs-main").setActiveTab("minishop-tabs-goods");
                    Ext.getCmp("minishop-tabs-goods-inner").setActiveTab("minishop-tabs-goods-inner-goods");
                    Ext.getCmp("minishop-grid-goods").editGoods("","", row);
                } else if (action == "tab" && typeof item != "undefined") {
                    Ext.getCmp("minishop-tabs-main").setActiveTab(Number(item));
                }
            });
        </script>');
    }

    public function getTemplateFile() { return $this->minishop->config['templates_path'] . 'home.tpl'; }
}
