
// Popup window with order properties
miniShop.window.EditOrder = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('ms.window.editorder')
        ,id: this.ident
        ,width: 700
        ,labelAlign: 'left'
        ,labelWidth: 200
        ,modal: true
        ,url: miniShop.config.connector_url
        ,action: 'mgr/orders/update'
        ,fields: [{
            xtype: 'modx-tabs'
            ,autoHeight: true
            ,deferredRender: false
            ,style: 'padding: 0 5px;'
            ,bodyStyle: 'padding-top: 10px;'
            ,stateful: true
            ,stateId: 'ms-tabs-orders'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return { activeTab:this.items.indexOf(this.getActiveTab()) };
            }
            ,items: [{
                title: _('ms.order')
                ,layout: 'form'
                // First tab
                ,items: [{
                    border: false
                    ,layout: 'form'
                    ,items: [
                        {xtype: 'hidden',name: 'id'}
                        ,{xtype: 'displayfield',name: 'num',id: this.ident+'-num',fieldLabel: _('ms.num')}
                        ,{xtype: 'displayfield',name: 'sum',id: this.ident+'-sum',fieldLabel: _('ms.sum')}
                        ,{xtype: 'displayfield',name: 'weight',id: this.ident+'-weight',fieldLabel: _('ms.weight')}
                        ,{xtype: 'displayfield',name: 'created',id: this.ident+'-created',fieldLabel: _('ms.created')}
                        ,{xtype: 'displayfield',name: 'fullname',id: this.ident+'-fullname',fieldLabel: _('ms.fullname')}
                        ,{xtype: 'displayfield',name: 'email',id: this.ident+'-email',fieldLabel: _('ms.email')}
                        ,{xtype: 'minishop-filter-warehouse',name: 'wid',hiddenName: 'wid',id: this.ident+'-warehouse',fieldLabel: _('ms.warehouse'),anchor: '70%'}
                        ,{xtype: 'minishop-filter-delivery',name: 'delivery',hiddenName: 'delivery',id: this.ident+'-delivery',fieldLabel: _('ms.delivery'),anchor: '70%'}
                        ,{xtype: 'minishop-filter-payment',name: 'payment',hiddenName: 'payment',id: this.ident+'-payment',fieldLabel: _('ms.payment'),anchor: '70%'}
                        ,{xtype: 'minishop-filter-status',name: 'status',hiddenName: 'status',id: this.ident+'-status',fieldLabel: _('ms.status'),anchor: '70%'}
                        ,{xtype: 'textarea',name: 'comment',id: this.ident+'-comment',fieldLabel: _('ms.comment'),anchor: '90%',autoHeight: false,height: 50}
                    ]
                }]
                // Second tab
            },{
                id: this.ident+'-goods'
                ,title: _('ms.goods')
                ,items: [{
                    xtype: 'minishop-grid-orderedgoods'
                    ,oid: oid
                    ,baseParams: {action: 'mgr/orderedgoods/getlist',oid: oid}
                }]
                // Third tab
            },{
                id: this.ident+'-address'
                ,title: _('ms.address')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,style: 'background: transparent;'
                ,items: [
                    {xtype: 'hidden',name: 'addr_id'}
                    ,{xtype: 'textfield',name: 'addr_receiver',fieldLabel: _('ms.receiver'),anchor: '90%'}
                    ,{xtype: 'textfield',name: 'addr_phone',fieldLabel: _('ms.phone')}
                    ,{xtype: 'numberfield',name: 'addr_index',fieldLabel: _('ms.index')}
                    ,{xtype: 'textfield',name: 'addr_country',fieldLabel: _('ms.country')}
                    ,{xtype: 'textfield',name: 'addr_region',fieldLabel: _('ms.region')}
                    ,{xtype: 'textfield',name: 'addr_city',fieldLabel: _('ms.city')}
                    ,{xtype: 'textfield',name: 'addr_metro',fieldLabel: _('ms.metro')}
                    ,{xtype: 'textfield',name: 'addr_street',fieldLabel: _('ms.street'),anchor: '80%'}
                    ,{xtype: 'textfield',name: 'addr_building',fieldLabel: _('ms.building'),width: 100}
                    ,{xtype: 'textfield',name: 'addr_room',fieldLabel: _('ms.room'),width: 100}
                    ,{xtype: 'textarea',name: 'addr_comment',id: this.ident+'-addrcomment',fieldLabel: _('ms.comment'),anchor: '90%',autoHeight: false,height: 50}
                ]
                // Fourth tab
            },{
                id: this.ident+'-orderhistory'
                ,title: _('ms.orderhistory')
                ,items: [{
                    id: this.ident+'-orderhistory-grid'
                    ,xtype: 'minishop-grid-log'
                    ,baseParams: {action: 'mgr/log/getlist',oid: oid}
                }]
                ,listeners: {
                    activate : {fn: function(){
                        Ext.getCmp(this.ident+'-orderhistory-grid').refresh();
                    }, scope: this}
                }
            }
            ]
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: function() {changed = 1; this.submit() }
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() {changed = 0; this.hide(); }
        },
            {
                text: config.saveBtnText || _('save_and_close')
                ,scope: this
                ,handler: function() {changed = 1; this.submit() }
            }]
    });
    miniShop.window.EditOrder.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.EditOrder,MODx.Window);
Ext.reg('minishop-window-editorder',miniShop.window.EditOrder);


miniShop.window.addOrderedGoods = function(config) {
    config = config || {};
    this.ident = config.ident || 'mecitem'+Ext.id();

    Ext.applyIf(config,{
        title: _('ms.orderedgoods.add')
        ,id: this.ident
        ,width: 500
        ,url: miniShop.config.connector_url
        ,action: 'mgr/orderedgoods/add'
        ,labelAlign: 'left'
        ,labelWidth: 150
        ,height: 150
        ,autoHeight: true
        ,bodyStyle: "padding: 5px;"
        ,html: _('ms.orderedgoods.add_desc')
        ,fields: [
            {xtype: 'hidden',name: 'id',id: 'minishop-'+this.ident+'-id'}
            ,{xtype: 'hidden',name: 'oid',value: config.oid,id: 'minishop-'+this.ident+'-oid'}
            ,{xtype: 'minishop-combo-goods',fieldLabel: _('ms.goods'),hiddenName: 'gid',name: 'gid',id: 'minishop-'+this.ident+'-goods',allowBlank:false,width: 300}
            ,{xtype: 'numberfield',fieldLabel: _('ms.goods.num'),name: 'num',id: 'minishop-'+this.ident+'-num',allowBlank:false,width: 50}
            ,{xtype: 'numberfield',fieldLabel: _('ms.price'),name: 'price',id: 'minishop-'+this.ident+'-price',width: 50}
            ,{xtype: 'numberfield',fieldLabel: _('ms.weight'),name: 'weight',id: 'minishop-'+this.ident+'-weight',width: 50}
            ,{xtype: 'textarea',fieldLabel: _('ms.goods.data'),name: 'data',id: 'minishop-'+this.ident+'-data',width: 300}
        ]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: _('close')
            ,scope: this
            ,handler: function() { this.hide();}
        },{
            text: _('save_and_close')
            ,scope: this
            ,handler: function() { this.submit();}
        }]
    });
    miniShop.window.addOrderedGoods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.addOrderedGoods,MODx.Window);
Ext.reg('minishop-window-orderedgoods',miniShop.window.addOrderedGoods);




miniShop.window.createGoods = function(config) {
    config = config || {};

    this.ident = miniShop.tempids.createGoods = config.ident || 'qcr'+Ext.id();
    Ext.applyIf(config, {
        title: _('ms.goods.create')
        ,id: this.ident
        ,width: 700
        ,modal: true
        ,labelAlign: 'left'
        ,labelWidth: 150
        ,url: miniShop.config.connector_url
        ,action: 'mgr/goods/create'
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,activeTab: config.activeTab || 0
            ,bodyStyle: { background: 'transparent' }
            ,deferredRender: false
            ,autoHeight: true
            ,stateful: true
            ,stateId: 'ms-tabs-goods'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return { activeTab:this.items.indexOf(this.getActiveTab()) };
            }
            ,items: [{
                id: 'modx-'+this.ident+'-resource'
                ,title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelAlign: 'top'
                ,labelWidth: 100
                ,items: [{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .5
                        ,border: false
                        ,layout: 'form'
                        ,items: [
                            {xtype: 'textfield',name: 'pagetitle',id: 'modx-'+this.ident+'-pagetitle',fieldLabel: _('pagetitle'),anchor: '100%',allowBlank: false}
                            ,{xtype: 'textfield',name: 'longtitle',id: 'modx-'+this.ident+'-longtitle',fieldLabel: _('long_title'),anchor: '100%'}
                            ,{xtype: 'textarea',name: 'description',id: 'modx-'+this.ident+'-description',fieldLabel: _('description'),anchor: '100%',grow: false,height: 50}
                            ,{xtype: 'textarea',name: 'introtext',id: 'modx-'+this.ident+'-introtext',fieldLabel: _('introtext'),anchor: '100%',height: 50}
                            ,{xtype: 'xcheckbox',name: 'deleted',id: 'modx-'+this.ident+'-deleted',boxLabel: _('deleted'),description: _('resource_delete_help'),inputValue: 1,checked: false}
                            ,{xtype: 'xcheckbox',name: 'clearCache',id: 'modx-'+this.ident+'-clearcache',boxLabel: _('clear_cache_on_save'),description: _('clear_cache_on_save_msg'),inputValue: 1,checked: true}
                        ]
                    },{
                        columnWidth: .5
                        ,border: false
                        ,layout: 'form'
                        ,items: [
                            {xtype: 'minishop-combo-goodstemplate',id: 'modx-'+this.ident+'-template',fieldLabel: _('template'),editable: false,anchor: '100%',value: miniShop.config.ms_goods_tpls[0]}
                            ,{xtype: 'minishop-filter-category',id: 'modx-'+this.ident+'-category', name: 'parent',fieldLabel: _('ms.category'),baseParams: {action: 'mgr/combo/cats_and_goods',addall: 0},anchor: '100%',hiddenName: 'parent'}
                            ,{xtype: 'textfield',name: 'alias',id: 'modx-'+this.ident+'-alias',fieldLabel: _('alias'),anchor: '100%'}
                            ,{xtype: 'textfield',name: 'menutitle',id: 'modx-'+this.ident+'-menutitle',fieldLabel: _('resource_menutitle'),anchor: '100%'}
                            ,{xtype: 'xcheckbox',name: 'published',id: 'modx-'+this.ident+'-published',boxLabel: _('resource_published'),description: _('resource_published_help'),inputValue: 1,checked: MODx.config.publish_default == '1' && config.disable_categories ? 1 : 0}
                            ,{xtype: 'xcheckbox',name: 'hidemenu',id: 'modx-'+this.ident+'-hidemenu',boxLabel: _('resource_hide_from_menus'),description: _('resource_hide_from_menus_help'),inputValue: 1,checked: MODx.config.hidemenu_default == '1' && config.disable_categories ? 1 : 0}
                            ,{xtype: 'xcheckbox',name: 'searchable',id: 'modx-'+this.ident+'-searchable',boxLabel: _('resource_searchable'),description: _('resource_searchable_help'),inputValue: 1,checked: MODx.config.search_default == '1' && config.disable_categories  ? 1 : 0}
                            ,{xtype: 'xcheckbox',name: 'cacheable',id: 'modx-'+this.ident+'-cacheable',boxLabel: _('resource_cacheable'),description: _('resource_cacheable_help'),inputValue: 1,checked: MODx.config.cache_default == '1' && config.disable_categories  ? 1 : 0}
                        ]
                    }]
                },{xtype: config.record.richtext ? (typeof Tiny!='undefined') ? 'tinymce' :'htmleditor' : 'textarea',name: 'content',id: 'modx-'+this.ident+'-content', fieldLabel: _('content'),anchor: '100%',height: 150}
                    ,{xtype: 'xcheckbox',name: 'richtext',id: 'modx-'+this.ident+'-richtext',boxLabel: _('resource_richtext'),description: _('resource_richtext_help'),inputValue: 1,checked: MODx.config.richtext_default == '1' && config.disable_categories  ? 1 : 0}
                    ,{xtype: 'hidden',name: 'class_key',value: 'modDocument'}
                    ,{xtype: 'hidden',name: 'context_key'}
                    ,{xtype: 'hidden',name: 'content_type' ,value: 1}
                    ,{xtype: 'hidden',name: 'content_dispo',value: 0}
                    ,{xtype: 'hidden',name: 'isfolder' ,value: 0}
                ]
            },{
                id: 'modx-'+this.ident+'-properties'
                ,title: _('ms.properties')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelAlign: 'left'
                ,labelWidth: 200
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: [
                    {xtype: 'hidden',name: 'id'}
                    ,{xtype: 'hidden',name: 'wid'}
                    ,{xtype: 'textfield',name: 'article',fieldLabel: _('ms.article')}
                    ,{xtype: 'numberfield',name: 'price',fieldLabel: _('ms.price')}
                    ,{xtype: 'numberfield',name: 'weight',decimalPrecision: 3, fieldLabel: _('ms.weight')}
                    ,{xtype: 'ms-combo-browser', openTo: config.record.img, name: 'img',fieldLabel: _('ms.img'),anchor: '100%'}
                    ,{xtype: 'numberfield',name: 'remains',fieldLabel: _('ms.remains')}
                    ,{xtype: 'textfield',name: 'reserved',disabled: true,fieldLabel: _('ms.reserved')}
                    ,{xtype: 'ms-superbox-tags', name: 'tags[]', value: config.record.tags, fieldLabel: _('ms.tags')}
                    ,{xtype: 'textfield',name: 'add1',fieldLabel: _('ms.goods.add1'),anchor: '100%'}
                    ,{xtype: 'textfield',name: 'add2',fieldLabel: _('ms.goods.add2'),anchor: '100%'}
                    ,{xtype: 'textarea',name: 'add3',fieldLabel: _('ms.goods.add3'),autoHeight: false,anchor: '100%',height: 100}
                    ,{xtype: 'checkbox',name: 'duplicate',value: 1,style: 'padding: 10px;',fieldLabel: _('ms.goods.duplicate'),description: _('ms.goods.duplicate.desc')}
                ]
            },{
                id: 'modx-'+this.ident+'-tvs'
                ,title: 'TVs'
                ,items: [{
                    xtype: 'minishop-grid-tvs'
                    ,disabled: config.disable_categories
                    ,baseParams: {
                        action: 'mgr/goods/tv/getlist'
                        ,gid: gid
                    }
                }]
            },{
                id: 'modx-'+this.ident+'-gallery'
                ,title: _('ms.gallery')
                ,items: [{
                    xtype: 'minishop-grid-gallery'
                    ,disabled: config.disable_categories
                    ,baseParams: {
                        action: 'mgr/goods/gallery/getlist'
                        ,gid: gid
                    }
                    ,gid: gid
                }]
            },{
                id: 'modx-'+this.ident+'-categories'
                ,title: _('ms.categories')
                ,items: [{
                    xtype: 'minishop-grid-categories'
                    ,disabled: config.disable_categories
                    ,baseParams: {
                        action: 'mgr/goods/getcatlist'
                        ,gid: gid
                    }

                }]
            }]
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn:  function() {changed = 1; this.submit() }
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() {this.hide(); }
        },{
            xtype: 'tbfill'
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: function() {changed = 1; this.submit() }
        }]
    });
    miniShop.window.createGoods.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.window.createGoods, MODx.Window);
Ext.reg('minishop-window-creategoods', miniShop.window.createGoods);
