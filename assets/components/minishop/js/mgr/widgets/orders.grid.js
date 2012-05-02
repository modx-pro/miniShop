miniShop.grid.Orders = function(config) {
	config = config || {};

	this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
		,tpl : new Ext.Template('<p class="desc">{comment}</p>')
		,renderer : function(v, p, record){return record.data.comment != '' ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';}
	});

	Ext.applyIf(config,{
		id: 'minishop-grid-orders'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/orders/getlist'}
		,plugins: this.exp
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,fields: ['id','uid','fullname','num','wid','warehousename','status','statusname','sum','weight','created','updated','comment']
		,columns: [this.exp
			,{header: _('id'),dataIndex: 'id',hidden: true,sortable: true,width: 50}
			,{header: _('ms.uid'),dataIndex: 'uid',hidden: true,width: 50}
			,{header: _('ms.fullname'),dataIndex: 'fullname',width: 100}
			,{header: _('ms.num'),dataIndex: 'num',sortable: true,width: 80}
			,{header: _('ms.warehouse'),dataIndex: 'wid',hidden: true,width: 50}
			,{header: _('ms.warehouse'),dataIndex: 'warehousename',hidden: true,width: 50}
			,{header: _('ms.status'),dataIndex: 'status',renderer: this.renderStatus,sortable: true,width: 50}
			,{header: _('ms.sum'),dataIndex: 'sum',sortable: true,width: 50}
			,{header: _('ms.weight'),dataIndex: 'weight',sortable: true,width: 50}
			,{header: _('ms.created'),dataIndex: 'created',sortable: true,width: 100}
			,{header: _('ms.updated'),dataIndex: 'updated',sortable: true,width: 100}
			,{header: _('ms.comment'),dataIndex: 'comment',hidden: true}
		]
		,tbar: [
			'<strong>' + _('ms.warehouse') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-warehouse'
			,id: 'orders-filter-warehouse'
			,listeners: {select: {fn: this.filterByWarehouse, scope:this}}
		},{
			xtype: 'tbspacer'
			,width: 10
		},
			'<strong>' + _('ms.status') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-status'
			,id: 'orders-filter-status'
			,width: 200
			,baseParams: {action:  'mgr/status/getcombo',addall: 1}
			,listeners: {
				select: {fn: this.filterByStatus, scope:this}
			}
		},{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: 'minishop-orders-filter-byquery'
			,listeners: {
				render: {fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}
			}
		},{
			xtype: 'minishop-filter-clear'
			,listeners: {click: {fn: this.FilterClear, scope: this}}
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.editOrder(grid, e, row);
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
	,renderStatus: function(v) {
		if (miniShop.config.statuses[v]) {
			var name = miniShop.config.statuses[v].name;
			var color = miniShop.config.statuses[v].color;
			return '<span style="color: #'+color+'">'+name+'</span>';
		}
	}
	,filterByWarehouse: function(cb) {
		this.getStore().baseParams['warehouse'] = cb.value;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,filterByStatus: function(cb) {
		this.getStore().baseParams['status'] = cb.value;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,removeOrder: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ms.orders.remove')
			,text: _('ms.orders.remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/orders/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.orders.edit')
			,handler: this.editOrder
		});
		m.push('-');
		m.push({
			text: _('ms.orders.remove')
			,handler: this.removeOrder
		});
		this.addContextMenuItem(m);
	}
	,editOrder: function(btn, e, row) {
		if (typeof(row) != 'undefined') {
			oid = row.data.id
		}
		else {
			oid = this.menu.record.id
		}
		changed = 0;
		MODx.Ajax.request({
			url: miniShop.config.connector_url
			,params: {
				action: 'mgr/orders/get'
				,id: oid
			}
			,listeners: {
				'success': {fn:function(r) {
					var pr = r.object;
					
					var w = MODx.load({
						xtype: 'minishop-window-editorder'
						,record: pr
						,listeners: {
							'success':{fn:function() {
							},scope:this}
							,'hide':{fn:function() {
								if (changed == 1) {
									Ext.getCmp('minishop-grid-orders').store.reload();
								}
								changed = 0;
							}}
						}
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


// Popup window with order properties
miniShop.window.EditOrder = function(config) {
	config = config || {};
	this.ident = config.ident || 'qur'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.window.editorder')
		,id: this.ident
		,width: 700
		,url: miniShop.config.connector_url
		,action: 'mgr/warehouse/create'
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


// History of changing the order 
miniShop.grid.Log = function(config) {
	config = config || {};
	this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
		,tpl : new Ext.Template('<p class="desc">{comment}</p>')
		,renderer : function(v, p, record){return record.data.comment != null ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';}
	});
	Ext.applyIf(config,{
		id: this.ident+'-grid-log'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/log/getlist',type: 'status',operation: 'change'}
		,fields: ['oid','iid','type','old','new','name','uid','username','ip','timestamp','comment']
		,pageSize: 10
		,autoHeight: true
		,plugins: this.exp
		,paging: true
		,remoteSort: true
		,columns: [this.exp
			,{header: _('ms.oid'),dataIndex: 'oid',hidden: true}
			,{header: _('ms.iid'),dataIndex: 'iid',hidden: true}
			,{header: _('ms.type'),dataIndex: 'type',width: 50}
			,{header: _('ms.log.old'),dataIndex: 'old',sortable: true,hidden: true,width: 50}
			,{header: _('ms.log.new'),dataIndex: 'new',sortable: true,hidden: true,width: 50}
			,{header: _('ms.name'),dataIndex: 'name',width: 100}
			,{header: _('ms.uid'),dataIndex: 'uid',sortable: true,hidden: true,width: 50}
			,{header: _('ms.username'),dataIndex: 'username',sortable: false,width: 50}
			,{header: _('ms.ip'),dataIndex: 'ip',hidden: true,sortable: true,width: 50}
			,{header: _('ms.timestamp'),dataIndex: 'timestamp',sortable: true,width: 70}
			,{header: _('ms.comment'),dataIndex: 'comment',hidden: true}
		]
	});
	miniShop.grid.Log.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Log,MODx.grid.Grid);
Ext.reg('minishop-grid-log',miniShop.grid.Log);



// Table with ordered goods
miniShop.grid.Goods = function(config) {
	config = config || {};
	
	this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
		,tpl : new Ext.Template('<p class="desc">{data_view}</p>')
		,renderer : function(v, p, record){return record.data.data_view != '' ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';}
	});
	
	Ext.applyIf(config,{
		id: this.ident+'-grid-goods'
		,url: miniShop.config.connector_url
		,fields: ['id','gid','oid','name','num','price','weight','sum','data','data_view']
		,pageSize: 10
		,autoHeight: true
		,paging: true
		,plugins: this.exp
		,remoteSort: true
		,columns: [this.exp
			,{header: _('id'),dataIndex: 'id',hidden: true,sortable: true,width: 35}
			,{header: _('ms.gid'),dataIndex: 'gid',hidden: true,sortable: true,width: 35}
			,{header: _('ms.goods.name'),dataIndex: 'name',width: 100}
			,{header: _('ms.goods.num'),dataIndex: 'num',sortable: true,width: 50}
			,{header: _('ms.price'),dataIndex: 'price',sortable: true,width: 50}
			,{header: _('ms.weight'),dataIndex: 'weight',sortable: true,width: 50}
			,{header: _('ms.goods.sum'),dataIndex: 'sum',sortable: true,width: 50}
			,{header: _('ms.goods.data'),dataIndex: 'data_view',hidden: true}
		]
		,tbar: [{
			text: _('ms.orderedgoods.add')
			,handler: this.addGoods
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateGoods(grid, e, row);
			}
		}
	});
	miniShop.grid.Goods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Goods,MODx.grid.Grid, {
	windows: {}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.orderedgoods.update')
			,handler: this.updateGoods
		});
		m.push('-');
		m.push({
			text: _('ms.orderedgoods.remove')
			,handler: this.removeGoods
		});
		this.addContextMenuItem(m);
	}
	,addGoods: function(btn,e) {
		this.windows.addOrderedGoods = MODx.load({
			xtype: 'minishop-window-orderedgoods'
			,title: _('ms.orderedgoods.add')
			,oid: this.oid
			,newrecord: 1
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
			}
		});
		this.windows.addOrderedGoods.show(e.target);
	}
	,updateGoods: function(btn,e,row) {
		if (typeof(row) != 'undefined') {
			var record = row.data;
		}
		else {
			var record = this.menu.record;
		}
		this.windows.addOrderedGoods = MODx.load({
			xtype: 'minishop-window-orderedgoods'
			,title: record.name
			,action: 'mgr/orderedgoods/update'
			,oid: this.oid
			,newrecord: 0
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
			}
		});
		this.windows.addOrderedGoods.fp.getForm().reset();
		this.windows.addOrderedGoods.fp.getForm().setValues(record);
		this.windows.addOrderedGoods.show(e.target);
	}
	,removeGoods: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ms.orderedgoods.remove')
			,text: _('ms.orderedgoods.remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/orderedgoods/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
});

Ext.reg('minishop-grid-orderedgoods',miniShop.grid.Goods);


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
