// Панель с табами, пока отключено
/*
miniShop.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,baseCls: 'modx-formpanel'
		,items: [{
			border: false
			,cls: 'modx-page-header'
		},{
			xtype: 'modx-tabs'
			,bodyStyle: 'padding: 10px'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,activeItem: 0
			,hideMode: 'offsets'
			,items: [{
				title: _('ms.orders')
				,items: [{
					border: false
				},{
					xtype: 'minishop-grid-orders'
					,preventRender: true
				}]
			}]
		}]
	});
	miniShop.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.panel.Home,MODx.Panel);
Ext.reg('minishop-panel-home',miniShop.panel.Home);
*/


// Комбо статусов
MODx.combo.status = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'status'
		,hiddenName: 'status'
		,displayField: 'name'
		,valueField: 'id'
		,fields: ['id','name']
		//,pageSize: 10
		,value: miniShop.config.status
		,emptyText: _('ms.combo.select')
		,url: miniShop.config.connector_url
		,baseParams: {
			action:  'status/getcombo'
			,addall: 1
		}
	});
	MODx.combo.status.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.status,MODx.combo.ComboBox);
Ext.reg('minishop-filter-status',MODx.combo.status);


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