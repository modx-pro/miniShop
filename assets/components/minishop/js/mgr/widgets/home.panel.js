miniShop.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        border: false
        ,deferredRender: true
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('minishop')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'minishop-tabs-main'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,hideMode: 'offsets'
            ,stateful: true
            ,stateId: 'ms-tabpanel-home'
            ,stateEvents: ['tabchange']
            ,getState: function() {
                return { activeTab: this.items.indexOf(this.getActiveTab()) };
            }
            ,items: [{
                title: _('ms.orders')
                ,items: [{
                    html: '<p>'+_('ms.orders.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-orders'
                    ,preventRender: true
                }]
                ,listeners: {
                    activate : function(panel){
                        Ext.getCmp('minishop-grid-orders').refresh();
                    }
                }
            },{
                title: _('ms.goods')
                ,id: 'minishop-tabs-goods'
                ,items: [{
                    html: '<p>'+_('ms.goods.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'modx-tabs'
                    ,id: 'minishop-tabs-goods-inner'
                    ,defaults: {
                        border: false
                        ,autoHeight: true
                    }
                    ,border: true
                    ,hideMode: 'offsets'
                    ,stateful: true
                    ,stateId: 'ms-tabpanel-home-goods'
                    ,stateEvents: ['tabchange']
                    ,getState: function() {
                        return { activeTab:this.items.indexOf(this.getActiveTab())};
                    }
                    ,items: [{
                        title: _('ms.goods')
                        ,id: 'minishop-tabs-goods-inner-goods'
                        ,items: [{
                            xtype: 'minishop-grid-goods'
                            ,preventRender: true
                        }]
                    },{
                        title: _('ms.kits')
                        ,id: 'minishop-tabs-goods-inner-kits'
                        ,items: [{
                            xtype: 'minishop-grid-kits'
                            ,preventRender: true
                        }]
                    }]
                }]
            },{
                title: _('ms.warehouses')
                ,items: [{
                    html: '<p>'+_('ms.warehouses.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-warehouses'
                    ,preventRender: true
                }]
            },{
                title: _('ms.statuses')
                ,items: [{
                    html: '<p>'+_('ms.status.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-statuses'
                    ,preventRender: true
                }]
            },{
                title: _('ms.payments')
                ,items: [{
                    html: '<p>'+_('ms.payments.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-payments'
                    ,preventRender: true
                }]
            }]
        }]
    });
    miniShop.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.panel.Home, MODx.Panel);
Ext.reg('minishop-panel-home', miniShop.panel.Home);








// Поиск: строка и кнопка сброса
MODx.form.FilterByQuery = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'textfield'
        ,emptyText: _('search')
        ,width: 200
    });
    MODx.form.FilterByQuery.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.FilterByQuery,Ext.form.TextField);
Ext.reg('minishop-filter-byquery',MODx.form.FilterByQuery);

MODx.form.FilterClear = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'button'
        ,text: _('clear_filter')
    });
    MODx.form.FilterClear.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.FilterClear,Ext.Button);
Ext.reg('minishop-filter-clear',MODx.form.FilterClear);
/////////////////////////////////////////


// Комбобоксы статусов, складов и категорий товаров
MODx.combo.status = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'status'
        ,hiddenName: 'status'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,pageSize: 10
        ,value: miniShop.config.status
        ,emptyText: _('ms.combo.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/status/getcombo'
        }
    });
    MODx.combo.status.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.status,MODx.combo.ComboBox);
Ext.reg('minishop-filter-status',MODx.combo.status);

MODx.combo.category = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'category'
        ,hiddenName: 'category'
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,editable: true
        ,fields: ['pagetitle','id']
        ,pageSize: 10
        ,value: miniShop.config.category
        ,emptyText: _('ms.category.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/combo/cats_and_goods'
            ,addall: 1
        }
    });
    MODx.combo.category.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.category,MODx.combo.ComboBox);
Ext.reg('minishop-filter-category',MODx.combo.category);

MODx.combo.goods = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'goods'
        ,hiddenName: 'goods'
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,editable: true
        ,fields: ['pagetitle','id']
        ,pageSize: 10
        ,value: miniShop.config.goods
        ,emptyText: _('ms.goods.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/combo/cats_and_goods'
            ,mode: 'goods'
        }
    });
    MODx.combo.goods.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.goods,MODx.combo.ComboBox);
Ext.reg('minishop-combo-goods',MODx.combo.goods);

MODx.combo.warehouse = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'warehouse'
        ,hiddenName: 'warehouse'
        ,displayField: 'name'
        ,valueField: 'id'
        //,autoSelect: true
        //,editable: true
        ,fields: ['name','id']
        ,pageSize: 10
        ,value: miniShop.config.warehouse
        ,emptyText: _('ms.warehouse.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/warehouse/getcombo'
        }
    });
    MODx.combo.warehouse.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.warehouse,MODx.combo.ComboBox);
Ext.reg('minishop-filter-warehouse',MODx.combo.warehouse);

MODx.combo.delivery = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'delivery'
        ,hiddenName: 'delivery'
        ,displayField: 'name'
        ,valueField: 'id'
        //,autoSelect: true
        //,editable: true
        ,fields: ['name','id']
        ,pageSize: 10
        ,value: miniShop.config.warehouse
        ,emptyText: _('ms.delivery.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/delivery/getcombo'
        }
    });
    MODx.combo.delivery.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.delivery,MODx.combo.ComboBox);
Ext.reg('minishop-filter-delivery',MODx.combo.delivery);

MODx.combo.payment = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'payment'
        ,hiddenName: 'payment'
        ,displayField: 'name'
        ,valueField: 'id'
        //,autoSelect: true
        //,editable: true
        ,fields: ['name','id']
        ,pageSize: 10
        ,value: miniShop.config.payment
        ,emptyText: _('ms.payment.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/payment/getcombo'
        }
    });
    MODx.combo.payment.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.payment,MODx.combo.ComboBox);
Ext.reg('minishop-filter-payment',MODx.combo.payment);
/////////////////////////////////////////


// Комбобокс выбора чанка
MODx.combo.chunk = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'chunk'
        ,hiddenName: 'chunk'
        ,displayField: 'name'
        ,valueField: 'id'
        ,editable: true
        ,fields: ['id','name']
        ,pageSize: 20
        ,emptyText: _('ms.chunk.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/combo/snips_and_chunks'
            ,mode: 'chunks'
        }
    });
    MODx.combo.chunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.chunk,MODx.combo.ComboBox);
Ext.reg('minishop-combo-chunk',MODx.combo.chunk);
///////////////////////////////////////

// Комбобокс выбора сниппета
MODx.combo.snippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'snippet'
        ,hiddenName: 'snippet'
        ,displayField: 'name'
        ,valueField: 'id'
        ,editable: true
        ,fields: ['id','name']
        ,pageSize: 20
        ,emptyText: _('ms.snippet.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/combo/snips_and_chunks'
            ,mode: 'snippets'
        }
    });
    MODx.combo.snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.snippet,MODx.combo.ComboBox);
Ext.reg('minishop-combo-snippet',MODx.combo.snippet);
///////////////////////////////////////

// Комбобокс выбора шаблона товара
MODx.combo.template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template'
        ,hiddenName: 'template'
        ,displayField: 'name'
        ,valueField: 'id'
        //,editable: true
        ,fields: ['id','name']
        //,pageSize: 20
        ,emptyText: _('ms.template.select')
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/goods/gettpllist'
            ,kits: 0
        }
    });
    MODx.combo.template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.template,MODx.combo.ComboBox);
Ext.reg('minishop-combo-goodstemplate',MODx.combo.template);
///////////////////////////////////////


//Superbox-select for miniShop Tags
miniShop.combo.tags = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype:'superboxselect'
        ,allowBlank: true
        ,msgTarget: 'under'
        ,allowAddNewData: true
        ,addNewDataOnBlur : true
        ,resizable: true
        ,name: 'tags[]'
        ,anchor:'100%'
        ,minChars: 3
        ,store:new Ext.data.JsonStore({
             id:'tags-store'
            ,root:'results'
            ,autoLoad: true
            ,autoSave: false
            ,totalProperty:'total'
            ,fields:['tag']
            ,url: miniShop.config.connector_url
            ,baseParams: {action: 'mgr/goods/gettags'}
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
    miniShop.combo.tags.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.combo.tags,Ext.ux.form.SuperBoxSelect);
Ext.reg('ms-superbox-tags',miniShop.combo.tags);
///////////////////////////////////////


// Модифицированный modx-combo-browser для miniShop
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

    Ext.applyIf(config,{
        width: 300
        ,triggerAction: 'all'
    });
    miniShop.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(miniShop.combo.Browser,Ext.form.TriggerField,{
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
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,hideFiles: this.config.hideFiles || true
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.fullRelativeUrl);
                        this.fireEvent('select',data);
                    },scope:this}
                }
            });
        }
        this.browser.win.buttons[0].on('disable',function(e) {this.enable()})
        this.browser.win.tree.on('click', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
            },this
        )
        this.browser.win.tree.on('dblclick', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
                this.browser.hide()
            },this
        )
        this.browser.show(btn)
        return true;
    }
    ,onDestroy: function(){
        miniShop.combo.Browser.superclass.onDestroy.call(this);
    }
    ,getPath: function(n) {
        if (n.id == '/') {return '';}
        data = n.attributes;
        path = data.path + '/'

        return path;
    }
});
Ext.reg('ms-combo-browser',miniShop.combo.Browser);
/////////////////////////////////////////
