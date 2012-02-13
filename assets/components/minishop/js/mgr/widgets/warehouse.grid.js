miniShop.grid.Warehouses = function(config) {
    config = config || {};

    this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
        ,tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });

    Ext.applyIf(config,{
        id: 'minishop-grid-warehouses'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/warehouse/getlist'
        }
		//,save_action: 'mgr/warehouse/updatefromgrid'
		//,autosave: true
		,plugins: this.exp
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
		,clicksToEdit: 'auto'
		//,preventSaveRefresh: false
		,fields: ['id','name','currency','address','phone','email','description','permission']
        ,columns: [this.exp, {
            header: _('id')
            ,dataIndex: 'id'
            ,width: 35
			,sortable: true
        },{
            header: _('ms.name')
            ,dataIndex: 'name'
            ,width: 100
			,sortable: true
        },{
            header: _('ms.address')
            ,dataIndex: 'address'
            ,width: 150
			,sortable: true
        },{
            header: _('ms.phone')
            ,dataIndex: 'phone'
            ,width: 65
			,sortable: true
        },{
            header: _('ms.email')
            ,dataIndex: 'email'
            ,width: 100
			,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,hidden: true
        },{
            header: _('ms.permission')
            ,dataIndex: 'permission'
            ,hidden: true
        }]
        ,tbar: [{
            text: _('ms.warehouse.create')
            ,handler: this.createWarehouse
            ,scope: this
        },{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: 'minishop-waresouse-filter-byquery'
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
				this.updateWarehouse(grid, e, row);
			}
		}
    });
    miniShop.grid.Warehouses.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Warehouses,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('ms.warehouse.update')
            ,handler: this.updateWarehouse
        });
        m.push('-');
        m.push({
            text: _('ms.warehouse.remove')
            ,handler: this.removeWarehouse
        });
        this.addContextMenuItem(m);
    }
    ,FilterClear: function() {
        var s = this.getStore();
        s.baseParams.query = '';
        Ext.getCmp('minishop-waresouse-filter-byquery').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }
	,FilterByQuery: function(tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
	}
    ,createWarehouse: function(btn,e) {
        //if (!this.windows.createWarehouse) {
            this.windows.createWarehouse = MODx.load({
                xtype: 'minishop-window-warehouse-create'
				,title: _('ms.warehouse.create')
				,action: 'mgr/warehouse/create'
				,delivery_disabled: true
				,height: 150
				,autoHeight: true
				,record: {}
				,closeOnSave: true
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
        //}
        this.windows.createWarehouse.fp.getForm().reset();
        this.windows.createWarehouse.show(e.target);
    }

    ,updateWarehouse: function(btn,e,row) {
        if (this.menu.record && this.menu.record.id) {
			var record = this.menu.record;
		}
		else {
			var record = row.data;
		}

        //if (!this.windows.updateWarehouse) {
            this.windows.updateWarehouse = MODx.load({
                xtype: 'minishop-window-warehouse-create'
				,title: _('ms.warehouse.update')
                ,record: record
				,height: 150
				,action: 'mgr/warehouse/update'
                ,listeners: {
                    success: {fn:function() { this.refresh(); },scope:this}
                }
            });
			
        //}
        this.windows.updateWarehouse.fp.getForm().reset();
        this.windows.updateWarehouse.fp.getForm().setValues(record);
        this.windows.updateWarehouse.show(e.target);
    }
  
    ,removeWarehouse: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('ms.warehouse.remove')
            ,text: _('ms.warehouse.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/warehouse/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }

});
Ext.reg('minishop-grid-warehouses',miniShop.grid.Warehouses);




miniShop.window.CreateWarehouse = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.warehouse.create_change')
		,id: this.ident
		,width: 475
		,url: miniShop.config.connector_url
		,action: 'mgr/warehouse/create'
		,labelAlign: 'left'
		,labelWidth: 150
		,modal: true
		,fields: [{
			xtype: 'modx-tabs'
			,autoHeight: true
			,deferredRender: false
			,items: [{
				title: _('ms.main')
				,layout: 'form'
				,style: 'padding: 0 5px;'
				,bodyStyle: 'padding-top: 10px;'
				,labelWidth: 110
				// Поля основных параметров склада
				,items: [{
					xtype: 'hidden'
					,name: 'id'
					,id: 'minishop-'+this.ident+'-id'
				},{
					xtype: 'textfield'
					,fieldLabel: _('ms.name')
					,name: 'name'
					,id: 'minishop-'+this.ident+'-name'
					,width: 300
					,allowBlank: false
				},{
					xtype: 'textfield'
					,fieldLabel: _('ms.currency')
					,name: 'currency'
					,id: 'minishop-'+this.ident+'-currency'
					,description: _('ms.warehouses.desc.currency')
					,width: 100
				},{
					xtype: 'textarea'
					,fieldLabel: _('ms.address')
					,name: 'address'
					,id: 'minishop-'+this.ident+'-address'
					,width: 300
				},{
					xtype: 'textfield'
					,fieldLabel: _('ms.phone')
					,name: 'phone'
					,id: 'minishop-'+this.ident+'-phone'
					,width: 150
				},{
					xtype: 'textfield'
					,fieldLabel: _('ms.email')
					,name: 'email'
					,id: 'minishop-'+this.ident+'-email'
					,width: 200
				},{
					xtype: 'textfield'
					,fieldLabel: _('ms.permission')
					,name: 'permission'
					,id: 'minishop-'+this.ident+'-permission'
					,description: _('ms.permission.description')
					,width: 200
				},{
					xtype: 'textarea'
					,fieldLabel: _('ms.description')
					,name: 'description'
					,id: 'minishop-'+this.ident+'-description'
					,width: 300
				}]
			},{
				title: _('ms.delivery')
				,layout: 'form'
				//,disabled: config.delivery_disabled || false
				,items: [{
					xtype: 'minishop-grid-warehouse-delivery'
					,disabled: config.delivery_disabled || false
					,warehouse: config.record
				}]
				,listeners:{
					activate: function(panel){
						//Ext.getCmp('minishop-grid-warehouse-delivery').refresh();
					}
				}
			}]
		}]
		,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: _('close')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('save')
            ,scope: this
            ,handler: function() { this.submit(config.closeOnSave || false); }
        }]
    });
    miniShop.window.CreateWarehouse.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.CreateWarehouse,MODx.Window);
Ext.reg('minishop-window-warehouse-create',miniShop.window.CreateWarehouse);









miniShop.grid.WarehouseDelivery = function(config) {
    config = config || {};
    
	this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
        ,tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });
	
    Ext.applyIf(config,{
        id: 'minishop-grid-warehouse-delivery'
        ,url: miniShop.config.connector_url
		,pageSize: 5
        ,baseParams: {
            action: 'mgr/delivery/getlist'
            ,wid: config.warehouse.id
        }
		,autosave: true
        ,save_action: 'mgr/delivery/updatefromgrid'
        ,fields: ['id','wid','name','description','price','enabled']
        ,paging: true
		,plugins: this.exp
        ,remoteSort: true
        ,columns: [this.exp, {
            header: _('id')
            ,dataIndex: 'id'
			,hidden: true
        },{
            header: _('wid')
            ,dataIndex: 'wid'
			,hidden: true
        },{
            header: _('ms.name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('ms.description')
            ,dataIndex: 'description'
			,hidden: true
        },{
            header: _('ms.price')
            ,dataIndex: 'price'
            ,width: 50
            ,sortable: true
			,editor: { xtype: 'numberfield' }
        },{
            header: _('ms.enabled')
            ,dataIndex: 'enabled'
            ,width: 50
            ,sortable: true
			,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }]
        ,tbar: [{
            text: _('create')
            ,handler: this.createDelivery
            ,scope: this
        }]
    });
    miniShop.grid.WarehouseDelivery.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.WarehouseDelivery,MODx.grid.Grid, {
    windows: {}
    ,getMenu: function() {
        var m = [];
		m.push({
            text: _('ms.delivery.update')
            ,handler: this.updateDelivery
        });
        m.push('-');
        m.push({
            text: _('ms.delivery.remove')
            ,handler: this.removeDelivery
        });
        this.addContextMenuItem(m);
    }
	,createDelivery: function(btn,e) {
        //if (!this.windows.createDelivery) {
            this.windows.createDelivery = MODx.load({
                xtype: 'minishop-window-delivery-create'
				,title: _('ms.delivery.create')
				,height: 150
				,autoHeight: true
				,record: {}
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
        //}
        //this.windows.createDelivery.fp.getForm().reset();
        this.windows.createDelivery.show(e.target);
    }
    ,updateDelivery: function(btn,e) {
        if (!this.menu.record || !this.menu.record.id) return false;
        var record = this.menu.record;

        //if (!this.windows.updateWarehouse) {
            this.windows.updateDelivery = MODx.load({
                xtype: 'minishop-window-delivery-create'
				,title: _('ms.delivery.update')
                ,record: record
				,height: 150
				,autoHeight: true
				,action: 'mgr/delivery/update'
                ,listeners: {
                    success: {fn:function() { this.refresh(); },scope:this}
                }
            });
			
        //}
        this.windows.updateDelivery.fp.getForm().reset();
        this.windows.updateDelivery.fp.getForm().setValues(record);
        this.windows.updateDelivery.show(e.target);
    }
    ,removeDelivery: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('ms.delivery.remove')
            ,text: _('ms.delivery.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/delivery/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('minishop-grid-warehouse-delivery',miniShop.grid.WarehouseDelivery);




miniShop.window.CreateDelivery = function(config) {
    config = config || {};
    this.ident = config.ident || 'mecitem'+Ext.id();
	
	var wid = Ext.getCmp('minishop-grid-warehouse-delivery').config.warehouse.id;
	
    Ext.applyIf(config,{
        title: _('ms.delivery.create')
        ,id: this.ident
        ,height: 150
        ,width: 350
        ,url: miniShop.config.connector_url
        ,action: 'mgr/delivery/create'
		,labelAlign: 'left'
		,labelWidth: 100
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
			,value: id
		},{
            xtype: 'hidden'
            ,name: 'wid'
			,value: wid
		},{
            xtype: 'textfield'
            ,fieldLabel: _('ms.name')
            ,name: 'name'
            ,id: 'minishop-'+this.ident+'-name'
            ,width: 200
			,allowBlank: false
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('ms.price')
            ,name: 'price'
            ,id: 'minishop-'+this.ident+'-price'
            ,width: 100
        },{
            xtype: 'textarea'
            ,fieldLabel: _('ms.description')
            ,name: 'description'
            ,id: 'minishop-'+this.ident+'-description'
            ,width: 200
			,height: 50
        }]

    });
    miniShop.window.CreateDelivery.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.CreateDelivery,MODx.Window);
Ext.reg('minishop-window-delivery-create',miniShop.window.CreateDelivery);