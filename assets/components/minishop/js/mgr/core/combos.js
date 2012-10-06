/**
 * Abstract combo
 *
 * @var miniShop.combo.abstract
 * @extends MODx.combo.ComboBox
 * @param config
 */
miniShop.combo.abstract = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        url: miniShop.config.connector_url
        ,pageSize: 10
    });
    miniShop.combo.abstract.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.abstract, MODx.combo.ComboBox);


/**
 * @var miniShop.combo.status
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-filter-status
 */
miniShop.combo.status = function(config) {
    Ext.applyIf(config, {
        name: 'status'
        ,hiddenName: 'status'
        ,value: miniShop.config.status
        ,emptyText: _('ms.combo.select')
        ,baseParams: {
            action: 'mgr/status/getcombo'
        }
    });
    miniShop.combo.status.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.status, miniShop.combo.abstract);
Ext.reg('minishop-filter-status', miniShop.combo.status);

/**
 * @var miniShop.combo.category
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-filter-category
 */
miniShop.combo.category = function(config) {
    Ext.applyIf(config, {
        name: 'category'
        ,hiddenName: 'category'
        ,displayField: 'pagetitle'
        ,editable: true
        ,fields: ['pagetitle','id']
        ,value: miniShop.config.category
        ,emptyText: _('ms.category.select')
        ,baseParams: {
            action: 'mgr/combo/cats_and_goods'
            ,addall: 1
        }
    });
    miniShop.combo.category.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.category , miniShop.combo.abstract);
Ext.reg('minishop-filter-category', miniShop.combo.category );

/**
 * @var miniShop.combo.goods
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-combo-goods
 */
miniShop.combo.goods = function(config) {
    Ext.applyIf(config, {
        name: 'goods'
        ,hiddenName: 'goods'
        ,displayField: 'pagetitle'
        ,editable: true
        ,fields: ['pagetitle','id']
        ,value: miniShop.config.goods
        ,emptyText: _('ms.goods.select')
        ,baseParams: {
            action: 'mgr/combo/cats_and_goods'
            ,mode: 'goods'
        }
    });
    miniShop.combo.goods.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.goods, miniShop.combo.abstract);
Ext.reg('minishop-combo-goods', miniShop.combo.goods);

/**
 * @var miniShop.combo.warehouse
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-filter-warehouse
 */
miniShop.combo.warehouse = function(config) {
    Ext.applyIf(config, {
        name: 'warehouse'
        ,hiddenName: 'warehouse'
        ,value: miniShop.config.warehouse
        ,emptyText: _('ms.warehouse.select')
        ,baseParams: {
            action: 'mgr/warehouse/getList'
        }
    });
    miniShop.combo.warehouse.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.warehouse, miniShop.combo.abstract);
Ext.reg('minishop-filter-warehouse', miniShop.combo.warehouse);

/**
 * @var miniShop.combo.delivery
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-filter-delivery
 */
miniShop.combo.delivery = function(config) {
    Ext.applyIf(config, {
        name: 'delivery'
        ,hiddenName: 'delivery'
        ,emptyText: _('ms.delivery.select')
        ,baseParams: {
            action: 'mgr/delivery/getcombo'
        }
    });
    miniShop.combo.delivery.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.delivery, miniShop.combo.abstract);
Ext.reg('minishop-filter-delivery', miniShop.combo.delivery);

/**
 * @var miniShop.combo.payment
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-filter-payment
 */
miniShop.combo.payment = function(config) {
    Ext.applyIf(config, {
        name: 'payment'
        ,hiddenName: 'payment'
        ,value: miniShop.config.payment
        ,emptyText: _('ms.payment.select')
        ,baseParams: {
            action: 'mgr/payment/getcombo'
        }
    });
    miniShop.combo.payment.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.payment, miniShop.combo.abstract);
Ext.reg('minishop-filter-payment', miniShop.combo.payment);



/**
 * @var miniShop.combo.chunk
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-combo-chunk
 */
miniShop.combo.chunk = function(config) {
    Ext.applyIf(config, {
        name: 'chunk'
        ,hiddenName: 'chunk'
        ,editable: true
        ,pageSize: 20
        ,emptyText: _('ms.chunk.select')
        ,baseParams: {
            action: 'mgr/combo/snips_and_chunks'
            ,mode: 'chunks'
        }
    });
    miniShop.combo.chunk.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.chunk, miniShop.combo.abstract);
Ext.reg('minishop-combo-chunk', miniShop.combo.chunk);

/**
 * @var miniShop.combo.snippet
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-combo-snippet
 */
miniShop.combo.snippet = function(config) {
    Ext.applyIf(config, {
        name: 'snippet'
        ,hiddenName: 'snippet'
        ,editable: true
        ,pageSize: 20
        ,emptyText: _('ms.snippet.select')
        ,baseParams: {
            action: 'mgr/combo/snips_and_chunks'
            ,mode: 'snippets'
        }
    });
    miniShop.combo.snippet.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.snippet, miniShop.combo.abstract);
Ext.reg('minishop-combo-snippet', miniShop.combo.snippet);

/**
 * @var miniShop.combo.template
 * @extends miniShop.combo.abstract
 * @param config
 * @xtype minishop-combo-goodstemplate
 */
miniShop.combo.template = function(config) {
    Ext.applyIf(config, {
        name: 'template'
        ,hiddenName: 'template'
        ,emptyText: _('ms.template.select')
        ,baseParams: {
            action: 'mgr/goods/gettpllist'
            ,kits: 0
        }
    });
    miniShop.combo.template.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.template, miniShop.combo.abstract);
Ext.reg('minishop-combo-goodstemplate', miniShop.combo.template);



/**
 * @var miniShop.combo.tags
 * @extends Ext.ux.form.SuperBoxSelect
 * @param config
 * @xtype ms-superbox-tags
 */
miniShop.combo.tags = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'superboxselect'
        ,allowBlank: true
        ,msgTarget: 'under'
        ,allowAddNewData: true
        ,addNewDataOnBlur : true
        ,resizable: true
        ,name: 'tags[]'
        ,anchor: '100%'
        ,minChars: 3
        ,store: new Ext.data.JsonStore({
            id:'tags-store'
            ,root:'results'
            ,autoLoad: true
            ,autoSave: false
            ,totalProperty:'total'
            ,fields:['tag']
            ,url: miniShop.config.connector_url
            ,baseParams: {
                action: 'mgr/goods/gettags'
            }
        })
        ,mode: 'remote'
        ,displayField: 'tag'
        ,valueField: 'tag'
        ,triggerAction: 'all'
        ,extraItemCls: 'x-tag'
        ,listeners: {
            newitem: function(bs,v, f){
                var newObj = {
                    tag: v
                };
                bs.addItem(newObj);
            }
        }
    });
    miniShop.combo.tags.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.combo.tags, Ext.ux.form.SuperBoxSelect);
Ext.reg('ms-superbox-tags', miniShop.combo.tags);


/**
 * @var miniShop.combo.Browser
 * @extends Ext.form.TriggerField
 * @param config
 * @xtype ms-combo-browser
 */
miniShop.combo.Browser = function(config) {
    config = config || {};

    if (config.length != 0 && typeof config.openTo !== "undefined") {
        if (!/^\//.test(config.openTo)) {
            config.openTo = '/' + config.openTo;
        }
        if (!/$\//.test(config.openTo)) {
            var tmp = config.openTo.split('/')
            delete tmp[tmp.length - 1];
            tmp = tmp.join('/');
            config.openTo = tmp.substr(1)
        }
    }

    Ext.applyIf(config, {
        width: 300
        ,triggerAction: 'all'
    });
    miniShop.combo.Browser.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(miniShop.combo.Browser, Ext.form.TriggerField, {
    browser: null

    ,onTriggerClick : function(btn){
        if (this.disabled){
            return false;
        }

        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,id: Ext.id()
                ,multiple: true
                ,source: this.config.source || MODx.config.default_media_source
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,hideFiles: this.config.hideFiles || true
                ,listeners: {
                    select: {
                        fn: function(data) {
                            this.setValue(data.fullRelativeUrl);
                            this.fireEvent('select', data);
                        }
                        ,scope:this
                    }
                }
            });
        }
        this.browser.win.buttons[0].on('disable', function(e) {this.enable()});
        this.browser.win.tree.on('click', function(n,e) {
            path = this.getPath(n);
            this.setValue(path);
        },this);
        this.browser.win.tree.on('dblclick', function(n,e) {
            path = this.getPath(n);
            this.setValue(path);
            this.browser.hide();
        },this);
        this.browser.show(btn);
        return true;
    }

    ,onDestroy: function(){
        miniShop.combo.Browser.superclass.onDestroy.call(this);
    }

    ,getPath: function(n) {
        if (n.id == '/') {return '';}
        data = n.attributes;
        path = data.path + '/';

        return path;
    }
});
Ext.reg('ms-combo-browser', miniShop.combo.Browser);
