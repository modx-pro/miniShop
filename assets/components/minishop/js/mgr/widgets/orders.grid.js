
miniShop.grid.Orders = function(config) {
    config = config || {};
	
    this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
        ,tpl : new Ext.Template(
            '<p class="desc">{comment}</p>'
        )
    });
	
    Ext.applyIf(config,{
        id: 'minishop-grid-orders'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/orders/getlist'
        }
		,save_action: 'mgr/orders/updatefromgrid'
		,autosave: true
		,plugins: this.exp
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
		,clicksToEdit: 'auto'
		//,preventSaveRefresh: false
        ,fields: ['id','uid','fullname','num','wid','warehousename','status','statusname','sum','created','updated', 'comment']
        ,columns: [this.exp, {
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
			,hidden: true
			,sortable: true
        },{
            header: _('ms.uid')
            ,dataIndex: 'uid'
            ,width: 50
			,hidden: true
        },{
            header: _('ms.fullname')
            ,dataIndex: 'fullname'
            ,width: 100
        },{
            header: _('ms.num')
            ,dataIndex: 'num'
            ,width: 80
			,sortable: true
        },{
            header: _('ms.warehouse')
            ,dataIndex: 'wid'
            ,width: 50
			,hidden: true
        },{
            header: _('ms.warehousename')
            ,dataIndex: 'warehousename'
            ,width: 50
			,hidden: true
        },{
            header: _('ms.status')
            ,dataIndex: 'status'
            ,width: 50
			,renderer: this.renderStatus
			,sortable: true
			//,editor: {
				//xtype: 'minishop-combo-status'
				//,renderer: 'boolean'
			//}
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
            header: _('ms.updated')
            ,dataIndex: 'updated'
            ,width: 100
			,sortable: true
        },{
            header: _('ms.comment')
            ,dataIndex: 'comment'
			,hidden: true
        }]
        ,tbar: [
			'<strong>' + _('ms.warehouse') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-warehouse'
			,id: 'orders-filter-warehouse'
			,listeners: {
				select: {fn: this.filterByWarehouse, scope:this}
			}
        },{
			xtype: 'tbspacer'
			,width: 10
		},
			'<strong>' + _('ms.status') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-status'
			,id: 'orders-filter-status'
			,width: 200
			,baseParams: {
				action:  'mgr/status/getcombo'
				,addall: 1
			}
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
				this.editOrder(grid, e, row);
			}
			//,rowClick: this.startEditChange
			//,afteredit: this.getRowParams
			//,afterAutoSave: this.updateRow
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
/*
	,getRowParams: function(e) {
		this.editedRow = e;
	}
	,updateRow: function(e) {
		this.editedRow.record.data.updated = e.object.updated;
		//this.editedRow.record.data.status = e.object.status;
		this.editedRow.record.commit();
	}
*/
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
        if (this.menu.record && this.menu.record.id) {
			oid = this.menu.record.id
		}
		else {
			oid = row.data.id
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



miniShop.window.EditOrder = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('ms.window.editorder')
        ,id: this.ident
        ,width: 475
        ,url: miniShop.config.connector_url
		,action: 'mgr/warehouse/create'
		,labelAlign: 'left'
		,labelWidth: 150
		,modal: true
        ,url: miniShop.config.connector_url
        ,action: 'mgr/orders/update'
        ,fields: [{
            xtype: 'modx-tabs'
            ,autoHeight: true
            ,deferredRender: false
			,style: 'padding: 0 5px;'
			,bodyStyle: 'padding-top: 10px;'
            ,items: [{
                title: _('ms.order')
                //,layout: 'form'
                //,cls: 'modx-panel'
				// Первый таб
                ,items: [{
					border: false
					,layout: 'form'
					,items: [{
						xtype: 'hidden'
						,name: 'id'
					},{
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
						xtype: 'minishop-filter-warehouse'
						,name: 'wid'
						,hiddenName: 'wid'
						,id: this.ident+'-warehouse'
						,fieldLabel: _('ms.warehouse')
						,width: 250
					},{
						xtype: 'displayfield'
						,name: 'delivery'
						,id: this.ident+'-delivery'
						,fieldLabel: _('ms.delivery')
					},{
						xtype: 'minishop-filter-status'
						,name: 'status'
						,id: this.ident+'-status'
						,fieldLabel: _('ms.status')
						//,anchor: '100%'
					},{
						xtype: 'textarea'
						,name: 'comment'
						,id: this.ident+'-comment'
						,fieldLabel: _('ms.comment')
						,anchor: '70%'
						,height: 50
					}]
				}]
				// Второй таб
				},{
					id: this.ident+'-goods'
					,title: _('ms.goods')
					,items: [{
						xtype: 'minishop-grid-orderedgoods'
						,baseParams: {
							action: 'mgr/orderedgoods/getlist'
							,oid: oid
						}
						,listeners: {
							afterAutoSave: function() {
								changed = 1;
							}
						}
					}]
				// Третий таб
				},{
					id: this.ident+'-address'
					,title: _('ms.address')
					,layout: 'form'
					,cls: 'modx-panel'
					,style: 'background: transparent;'
					,items: [{
						xtype: 'hidden'
						,name: 'addr_id'
						//,allowBlank: false
					},{
						xtype: 'textfield'
						,name: 'addr_receiver'
						,fieldLabel: _('ms.receiver')
						//,allowBlank: false
						,anchor: '80%'
					},{
						xtype: 'textfield'
						,name: 'addr_phone'
						,fieldLabel: _('ms.phone')
						//,allowBlank: false
					},{
						xtype: 'numberfield'
						,name: 'addr_index'
						,fieldLabel: _('ms.index')
						//,allowBlank: false
					},{
						xtype: 'textfield'
						,name: 'addr_region'
						,fieldLabel: _('ms.region')
						//,allowBlank: false
					},{
						xtype: 'textfield'
						,name: 'addr_city'
						,fieldLabel: _('ms.city')
						//,allowBlank: false
					},{
						xtype: 'textfield'
						,name: 'addr_metro'
						,fieldLabel: _('ms.metro')
					},{
						xtype: 'textfield'
						,name: 'addr_street'
						,fieldLabel: _('ms.street')
						//,allowBlank: false
						,anchor: '80%'
					},{
						xtype: 'textfield'
						,name: 'addr_building'
						,fieldLabel: _('ms.building')
						//,allowBlank: false
						,width: 100
					},{
						xtype: 'textfield'
						,name: 'addr_room'
						,fieldLabel: _('ms.room')
						//,allowBlank: false
						,width: 100
					},{
						xtype: 'textarea'
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
					,items: [{
						xtype: 'minishop-grid-log'
						,baseParams: {
							action: 'mgr/log/getlist'
							,iid: oid
						}
					}]
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
		/*{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },*/
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







// Таблица с заказанными товарами
miniShop.grid.Goods = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: this.ident+'-grid-goods'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/orderedgoods/getlist'
        }
		,autosave: true
		,preventSaveRefresh: false
		,clicksToEdit: 'auto'
		,save_action: 'mgr/orderedgoods/updatefromgrid'
        ,fields: ['id','gid','oid','name','num','price','sum']
		,pageSize: 10
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true

        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,hidden: true
			,sortable: true
        },{
            header: _('ms.gid')
            ,dataIndex: 'gid'
            //,width: 30
			,hidden: true
			,sortable: true
        },{
            header: _('ms.goods.name')
            ,dataIndex: 'name'
            ,width: 100
        },{
            header: _('ms.goods.num')
            ,dataIndex: 'num'
            ,width: 50
			,editor: {
				xtype: 'numberfield'
			}
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
			,sortable: true
        }]
		/*
        ,tbar: [{
            text: _('minishop.item_create')
            ,handler: this.createItem
            ,scope: this
        }]
		*/
    });
    miniShop.grid.Goods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Goods,MODx.grid.Grid);
Ext.reg('minishop-grid-orderedgoods',miniShop.grid.Goods);





// История изменения статусов заказов
miniShop.grid.Log = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: this.ident+'-grid-log'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/log/getlist'
			,type: 'status'
			,operation: 'change'
        }
		//,autosave: true
		//,preventSaveRefresh: false
		//,clicksToEdit: 'auto'
		//,save_action: 'mgr/goods/updatefromgrid'
        ,fields: ['iid','type','operation','old','new','uid','ip','timestamp']
		,pageSize: 10
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true

        ,columns: [{
            header: _('ms.iid')
            ,dataIndex: 'iid'
			,hidden: true
        },{
            header: _('ms.log.old')
            ,dataIndex: 'old'
            ,width: 50
			,hidden: true
			,sortable: true
			,renderer: this.renderStatus
        },{
            header: _('ms.log.new')
            ,dataIndex: 'new'
            ,width: 50
			,sortable: true
			,renderer: this.renderStatus
        },{
            header: _('ms.uid')
            ,dataIndex: 'uid'
            ,width: 50
			,sortable: true
        },{
            header: _('ms.ip')
            ,dataIndex: 'ip'
            ,width: 50
			,hidden: true
			,sortable: true
        },{
            header: _('ms.timestamp')
            ,dataIndex: 'timestamp'
            ,width: 100
			,sortable: true
        }]
		/*
        ,tbar: [{
            text: _('minishop.item_create')
            ,handler: this.createItem
            ,scope: this
        }]
		*/
    });
    miniShop.grid.Log.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Log,MODx.grid.Grid,{
	renderStatus: function(v) {
		if (miniShop.config.statuses[v]) {
			var name = miniShop.config.statuses[v].name;
			var color = miniShop.config.statuses[v].color;
			return '<span style="color: #'+color+'">'+name+'</span>';
		}
    }
});
Ext.reg('minishop-grid-log',miniShop.grid.Log);