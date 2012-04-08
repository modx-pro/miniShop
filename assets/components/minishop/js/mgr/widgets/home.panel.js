miniShop.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('minishop')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header container'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
            ,cls: 'container'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            //,activeItem: 0
            ,hideMode: 'offsets'
			,stateful: true
			,stateId: 'ms-tabpanel-home'
			,stateEvents: ['tabchange']
			,getState:function() {
				return { activeTab:this.items.indexOf(this.getActiveTab()) };
			}
            ,items: [{
                title: _('ms.orders')
                ,items: [{
                    html: '<p>'+_('ms.orders.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'minishop-grid-orders'
					,preventRender: true
                }]
				,listeners: {
					activate : function(panel){
						//Ext.getCmp('minishop-grid-warehouses').refresh();
					}
				}
            },{
                title: _('ms.goods')
                ,items: [{
                    html: '<p>'+_('ms.goods.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'minishop-grid-goods'
                    ,preventRender: true
                }]
				,listeners: {
					activate : function(panel){
						//Ext.getCmp('minishop-grid-goods').refresh();
					}
				}
            },{
                title: _('ms.warehouses')
                ,items: [{
                    html: '<p>'+_('ms.warehouses.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'minishop-grid-warehouses'
                    ,preventRender: true
                }]
				,listeners: {
					activate : function(panel){
						//Ext.getCmp('minishop-grid-warehouses').refresh();
					}
				}
            },{
                title: _('ms.statuses')
                ,items: [{
                    html: '<p>'+_('ms.status.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'minishop-grid-statuses'
                    ,preventRender: true
                }]
				,listeners: {
					activate : function(panel){
						//Ext.getCmp('minishop-grid-statuses').refresh();
					}
				}
            },{
                title: _('ms.payments')
                ,items: [{
                    html: '<p>'+_('ms.payments.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'minishop-grid-payments'
                    ,preventRender: true
                }]
				,listeners: {
					activate : function(panel){
						//Ext.getCmp('minishop-grid-payments').refresh();
					}
				}
            }]
        }]
    });
    miniShop.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.panel.Home,MODx.Panel);
Ext.reg('minishop-panel-home',miniShop.panel.Home);













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
			action:  'mgr/status/getcombo'
		}
		//,onRender: function(tf) {
			//this.readOnly = true;
			//this.disabled = !this.initialConfig.submitValue;
			//MODx.StaticBoolean.superclass.onRender.apply(this, arguments);
			//this.on('change',this.onChange,this);
		//}
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
		//,autoSelect: true
		,editable: true
        ,fields: ['pagetitle','id']
        ,pageSize: 10
		,value: miniShop.config.category
		,emptyText: _('ms.category.select')
        ,url: miniShop.config.connector_url
		,baseParams: {
			action:  'mgr/goods/getcombo'
			,addall: 1
		}
    });
    MODx.combo.category.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.category,MODx.combo.ComboBox);
Ext.reg('minishop-filter-category',MODx.combo.category);

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
			action:  'mgr/warehouse/getcombo'
		}
    });
    MODx.combo.warehouse.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.warehouse,MODx.combo.ComboBox);
Ext.reg('minishop-filter-warehouse',MODx.combo.warehouse);
/////////////////////////////////////////


// Комбобокс выбора чанка
MODx.combo.chunk = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'chunk'
        ,hiddenName: 'chunk'
        ,displayField: 'name'
        ,valueField: 'name'
		//,autoSelect: true
		//,editable: true
        ,fields: ['id','name']
		,pageSize: 20
		,emptyText: _('ms.chunk.select')
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,baseParams: {
            action: 'getList'
        }
    });
    MODx.combo.chunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.chunk,MODx.combo.ComboBox);
Ext.reg('minishop-combo-chunk',MODx.combo.chunk);
///////////////////////////////////////

// Комбобокс выбора шаблона товара
MODx.combo.template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template'
        ,hiddenName: 'template'
        ,displayField: 'name'
        ,valueField: 'id'
		//,autoSelect: true
		//,editable: true
        ,fields: ['id','name']
		//,pageSize: 20
		,emptyText: _('ms.template.select')
        ,url: miniShop.config.connector_url
		,baseParams: {
			action:  'mgr/goods/gettpllist'
		}
    });
    MODx.combo.template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.template,MODx.combo.ComboBox);
Ext.reg('minishop-combo-goodstemplate',MODx.combo.template);
///////////////////////////////////////

// Комбобокс выбора сниппета
MODx.combo.snippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'snippet'
        ,hiddenName: 'snippet'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
		,pageSize: 20
		,emptyText: _('ms.snippet.select')
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,baseParams: {
            action: 'getList'
        }
    });
    MODx.combo.snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.snippet,MODx.combo.ComboBox);
Ext.reg('minishop-combo-snippet',MODx.combo.snippet);
///////////////////////////////////////



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
