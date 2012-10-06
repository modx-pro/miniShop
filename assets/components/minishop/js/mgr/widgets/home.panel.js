/**
 * @class miniShop.panel.Home
 * @extends MODx.Panel
 * @param {Object} config
 * @xtype minishop-panel-home
 */
miniShop.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        border: false
        ,deferredRender: true
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+ _('minishop') +'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'minishop-tabs-main'
            ,defaults: {
                border: false
                ,autoHeight: true
            }
            ,border: true
            ,hideMode: 'offsets'
            ,stateful: true
            ,stateId: 'ms-tabpanel-home'
            ,stateEvents: ['tabchange']
            ,getState: function() {
                return { activeTab: this.items.indexOf(this.getActiveTab()) };
            }
            ,items: [{
// Orders tab
                title: _('ms.orders')
                ,items: [{
                    html: _('ms.orders.intro_msg')
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-orders'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
                ,listeners: {
                    activate : function(panel){
                        Ext.getCmp('minishop-grid-orders').refresh();
                    }
                }
            },{
// Goods tab
                title: _('ms.goods')
                ,id: 'minishop-tabs-goods'
                ,items: [{
                    html: _('ms.goods.intro_msg')
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
                            ,cls: 'main-wrapper'
                        }]
                    },{
                        title: _('ms.kits')
                        ,id: 'minishop-tabs-goods-inner-kits'
                        ,items: [{
                            xtype: 'minishop-grid-kits'
                            ,preventRender: true
                            ,cls: 'main-wrapper'
                        }]
                    }]
                }]
            },{
// Warehouses tab
                title: _('ms.warehouses')
                ,items: [{
                    html: _('ms.warehouses.intro_msg')
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-warehouses'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            },{
// Statuses tab
                title: _('ms.statuses')
                ,items: [{
                    html: _('ms.status.intro_msg')
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-statuses'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            },{
// Payments tab
                title: _('ms.payments')
                ,items: [{
                    html: _('ms.payments.intro_msg')
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'minishop-grid-payments'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            }]
        }]
    });
    miniShop.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.panel.Home, MODx.Panel);
Ext.reg('minishop-panel-home', miniShop.panel.Home);
