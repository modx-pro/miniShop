// Осноыной объект магазина
var miniShop = function(config) {
	config = config || {};
	miniShop.superclass.constructor.call(this,config);
};
Ext.extend(miniShop,Ext.Component,{
	page:{},window:{},grid:{},form:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('minishop',miniShop);

miniShop = new miniShop();


// Старт при загрузке
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-layout'});
	MODx.load({ xtype: 'minishop-page-home'});
});


// Разметка страницы по умолчанию - нужна для Revo 2.2
MODx.Layout = function(config){
	config = config || {};
	Ext.applyIf(config,{
		applyTo: 'modx-content'	
		,id: 'modx-content'
	});
	MODx.Layout.superclass.constructor.call(this,config);
}
Ext.extend(MODx.Layout,Ext.Viewport);
Ext.reg('modx-layout',MODx.Layout);

// Контейнер личного кабинета
miniShop.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'minishop-grid-orders'
			//xtype: 'minishop-panel-home'
			,renderTo: 'minishop-panel-home-div'
		}]
	}); 
	miniShop.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.page.Home,MODx.Component);
Ext.reg('minishop-page-home',miniShop.page.Home);



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


// Таблица заказов
miniShop.grid.Orders = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'minishop-grid-orders'
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'orders/getlist'
		}
		,fields: ['id','fullname','num','warehousename','status','sum','created','updated']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [{
			header: _('id')
			,dataIndex: 'id'
			,width: 50
			,hidden: true
			,sortable: true
		},{
			header: _('ms.fullname')
			,dataIndex: 'fullname'
			,width: 100
			,hidden: true
		},{
			header: _('ms.num')
			,dataIndex: 'num'
			,width: 50
			,sortable: true
		},{
			header: _('ms.warehouse')
			,dataIndex: 'warehousename'
			,width: 50
			,hidden: true
		},{
			header: _('ms.sum')
			,dataIndex: 'sum'
			,width: 50
			,sortable: true
		},{
			header: _('ms.created')
			,dataIndex: 'created'
			,width: 100
			,sortable: true
		},{
			header: _('ms.status')
			,dataIndex: 'status'
			,width: 80
			,renderer: this.renderStatus
			,sortable: true
		},{
			header: _('ms.updated')
			,dataIndex: 'updated'
			,width: 100
			,sortable: true
		}]
		,tbar: [
			'<strong>' + _('ms.status') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-status'
			,id: 'orders-filter-status'
			,width: 200
			,listeners: {
				select: {fn: this.filterByStatus, scope:this}
			}
		},{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: 'minishop-orders-filter-byquery'
			,listeners: {
				render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}
			}
		},{
			xtype: 'minishop-filter-clear'
			,listeners: {
				click: {fn: this.FilterClear, scope: this}
			}
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.viewOrder(grid, e, row);
			}
		}
	});
    miniShop.grid.Orders.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Orders,MODx.grid.Grid,{
	windows: {}
	,FilterClear: function() {
		var s = this.getStore();
		s.baseParams.query = '';
		Ext.getCmp('minishop-orders-filter-byquery').reset();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,FilterByQuery: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,filterByStatus: function(cb) {
		this.getStore().baseParams['status'] = cb.value;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,renderStatus: function(v) {
		if (miniShop.config.statuses[v]) {
			var name = miniShop.config.statuses[v].name;
			var color = miniShop.config.statuses[v].color;
			return '<span style="color: #'+color+'">'+name+'</span>';
		}
	}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.vieworder')
			,handler: this.viewOrder
		});
		this.addContextMenuItem(m);
	}
	,viewOrder: function(btn, e, row) {
		if (this.menu.record && this.menu.record.id) {
			oid = this.menu.record.id
		}
		else {
			oid = row.data.id
		}
		MODx.Ajax.request({
			url: miniShop.config.connector_url
			,params: {
				action: 'orders/get'
				,id: oid
			}
			,listeners: {
				'success': {fn:function(r) {
					var pr = r.object;
					var w = MODx.load({
						xtype: 'minishop-window-vieworder'
						,record: pr
					});
					w.setValues(r.object);
					w.show(e.target,function() {
						Ext.isSafari ? w.setPosition(null,30) : w.center();
					},this);
				},scope:this}
			}
		});
	}
});
Ext.reg('minishop-grid-orders',miniShop.grid.Orders);



miniShop.window.ViewOrder = function(config) {
	config = config || {};
	this.ident = config.ident || 'qur'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.order')
		,id: this.ident+'-window-vieworder'
		,width: 600
		,labelAlign: 'left'
		,labelWidth: 200
		,modal: true
		,autoHeight: true
		,shadow: false
		,fields: [{
			xtype: 'modx-tabs'
			,bodyStyle: { background: 'transparent' }
			,autoHeight: true
			,deferredRender: false
			,items: [{
				title: _('ms.order')
				,layout: 'form'
				,cls: 'modx-panel'
				,bodyStyle: { background: 'transparent', padding: '10px' }
				,autoHeight: true
				,labelWidth: 200
				// Первый таб
				,items: [{
					border: false
					,layout: 'form'
					,items: [{
						xtype: 'displayfield'
						,name: 'num'
						,id: this.ident+'-num'
						,fieldLabel: _('ms.num')
					},{
						xtype: 'displayfield'
						,name: 'created'
						,id: this.ident+'-created'
						,fieldLabel: _('ms.created')
					},{
						xtype: 'displayfield'
						,name: 'fullname'
						,id: this.ident+'-fullname'
						,fieldLabel: _('ms.fullname')
					},{
						xtype: 'displayfield'
						,name: 'email'
						,id: this.ident+'-email'
						,fieldLabel: _('ms.email')
					},{
						xtype: 'displayfield'
						,name: 'delivery'
						,id: this.ident+'-delivery'
						,fieldLabel: _('ms.delivery')
					},{
						xtype: 'displayfield'
						,name: 'statusname'
						,id: this.ident+'-statusname'
						,fieldLabel: _('ms.statusname')
						//,anchor: '100%'
					}]
				}]
				// Второй таб
				},{
					id: this.ident+'-goods'
					,title: _('ms.goods')
					,style: 'background: transparent;padding-top: 10px;'
					,items: [{
						xtype: 'minishop-grid-goods'
						,baseParams: {
							action: 'orderedgoods/getlist'
							,oid: oid
						}
					}]
				// Третий таб
				},{
					id: this.ident+'-address'
					,title: _('ms.address')
					,layout: 'form'
					,cls: 'modx-panel'
					,autoHeight: true
					,forceLayout: true
					,labelWidth: 200
					,defaults: {autoHeight: true ,border: false}
					,style: 'background: transparent;'
					,bodyStyle: { background: 'transparent', padding: '10px' }
					,items: [{
						xtype: 'hidden'
						,name: 'addr_id'
					},{
						xtype: 'displayfield'
						,name: 'addr_receiver'
						,fieldLabel: _('ms.receiver')
						,anchor: '80%'
					},{
						xtype: 'displayfield'
						,name: 'addr_phone'
						,fieldLabel: _('ms.phone')
					},{
						xtype: 'displayfield'
						,name: 'addr_index'
						,fieldLabel: _('ms.index')
					},{
						xtype: 'displayfield'
						,name: 'addr_region'
						,fieldLabel: _('ms.region')
					},{
						xtype: 'displayfield'
						,name: 'addr_city'
					},{
						xtype: 'displayfield'
						,name: 'addr_metro'
						,fieldLabel: _('ms.metro')
					},{
						xtype: 'displayfield'
						,name: 'addr_street'
						,fieldLabel: _('ms.street')
						,anchor: '80%'
					},{
						xtype: 'displayfield'
						,name: 'addr_building'
						,fieldLabel: _('ms.building')
						,width: 100
					},{
						xtype: 'displayfield'
						,name: 'addr_room'
						,fieldLabel: _('ms.room')
						,width: 100
					},{
						xtype: 'displayfield'
						,name: 'addr_comment'
						,id: this.ident+'-addrcomment'
						,fieldLabel: _('ms.comment')
						,anchor: '70%'
						,height: 50
					}]
				// Четвертый таб
				},{
					id: this.ident+'-orderhistory'
					,title: _('ms.orderhistory')
					,style: 'background: transparent;padding-top: 10px;'
					,items: [{
						xtype: 'minishop-grid-history'
						,baseParams: {
							action: 'log/getlist'
							,iid: oid
						}
					}]
				}
			]
		}]
		,keys: [{
			key: Ext.EventObject.ENTER
			,shift: true
			,fn: false
			,scope: this
		}]
		,buttons: [{
			text: config.cancelBtnText || _('close')
			,scope: this
			,handler: function() {changed = 0; this.hide(); }
		}]
	});
	miniShop.window.ViewOrder.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.ViewOrder,MODx.Window);
Ext.reg('minishop-window-vieworder',miniShop.window.ViewOrder);


// Таблица с заказанными товарами
miniShop.grid.Goods = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: this.ident+'-grid-goods'
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'goods/getlist'
		}
		,fields: ['id','gid','oid','name','num','price','sum']
		,pageSize: 10
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [{
			header: _('ms.goods.name')
			,dataIndex: 'name'
			,width: 100
		},{
			header: _('ms.goods.num')
			,dataIndex: 'num'
			,width: 50
			,sortable: true
		},{
			header: _('ms.goods.price')
			,dataIndex: 'price'
			,width: 50
			,sortable: true
		},{
			header: _('ms.goods.sum')
			,dataIndex: 'sum'
			,width: 50
		}]
	});
	miniShop.grid.Goods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Goods,MODx.grid.Grid);
Ext.reg('minishop-grid-goods',miniShop.grid.Goods);


// История изменения статусов заказов
miniShop.grid.History = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: this.ident+'-grid-history'
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'history/getlist'
		}
		,fields: ['id','oid','statusname','timestamp']
		,pageSize: 10
		,autoHeight: true
		,paging: true
		,remoteSort: true

		,columns: [{
			header: _('ms.status')
			,dataIndex: 'statusname'
			,width: 50
			,sortable: true
		},{
			header: _('ms.timestamp')
			,dataIndex: 'timestamp'
			,width: 100
			,sortable: true
		}]
	});
	miniShop.grid.History.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.History,MODx.grid.Grid);
Ext.reg('minishop-grid-history',miniShop.grid.History);