miniShop.grid.Payments = function(config) {
	config = config || {};

	this.exp = new Ext.grid.RowExpander({
		expandOnDblClick: false
		,tpl : new Ext.Template(
			'<p class="desc">{description}</p>'
		)
	});

	Ext.applyIf(config,{
		id: 'minishop-grid-payments'
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'mgr/payment/getlist'
		}
		,plugins: this.exp
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,clicksToEdit: 'auto'
		,fields: ['id','name','description','snippet']
		,columns: [this.exp, 
			{header: _('id'),dataIndex: 'id',sortable: true,width: 35}
			,{header: _('ms.name'),dataIndex: 'name',sortable: true,width: 150}
			,{header: _('description'),dataIndex: 'description',hidden: true}
			,{header: _('snippet'),dataIndex: 'snippet'}
		]
		,tbar: [{
			text: _('ms.payment.create')
			,handler: this.createPayment
			,scope: this
		},{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: 'minishop-payments-filter-byquery'
			,listeners: {render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}}
		},{
			xtype: 'minishop-filter-clear'
			,listeners: {click: {fn: this.FilterClear, scope: this}}
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updatePayment(grid, e, row);
			}
		}
	});
	miniShop.grid.Payments.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Payments,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.payment.update')
			,handler: this.updatePayment
		});
		m.push('-');
		m.push({
			text: _('ms.payment.remove')
			,handler: this.removePayment
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
	,createPayment: function(btn,e) {
		this.windows.createPayment = MODx.load({
			xtype: 'minishop-window-payment-create'
			,title: _('ms.payment.create')
			,action: 'mgr/payment/create'
			,height: 150
			,autoHeight: true
			,record: {}
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
			}
		});
		this.windows.createPayment.fp.getForm().reset();
		this.windows.createPayment.show(e.target);
	}
	,updatePayment: function(btn,e,row) {
		if (typeof(row) != 'undefined') {
			var record = row.data;
		}
		else {
			var record = this.menu.record;
		}
		this.windows.updatePayment = MODx.load({
			xtype: 'minishop-window-payment-create'
			,title: record.name
			,record: record
			,height: 150
			,action: 'mgr/payment/update'
			,listeners: {
				success: {fn:function() { this.refresh(); },scope:this}
			}
		});
		this.windows.updatePayment.fp.getForm().reset();
		this.windows.updatePayment.fp.getForm().setValues(record);
		this.windows.updatePayment.show(e.target);
	}
	,removePayment: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ms.payment.remove')
			,text: _('ms.payment.remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/payment/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
});
Ext.reg('minishop-grid-payments',miniShop.grid.Payments);




miniShop.window.CreatePayment = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.payment.create_change')
		,id: this.ident
		,width: 600
		,url: miniShop.config.connector_url
		,action: 'mgr/payment/create'
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
				,items: [
					{xtype: 'hidden',name: 'id',id: 'minishop-'+this.ident+'-id'}
					,{xtype: 'textfield',fieldLabel: _('ms.name'),name: 'name',id: 'minishop-'+this.ident+'-name',allowBlank: false,width: 300}
					,{xtype: 'textarea',fieldLabel: _('ms.description'),name: 'description',id: 'minishop-'+this.ident+'-description',width: 300}
					,{xtype: 'minishop-combo-snippet',fieldLabel: _('snippet'),name: 'snippet',id: 'minishop-'+this.ident+'-snippet',width: 300}
				]
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
			text: _('save_and_close')
			,scope: this
			,handler: function() { this.submit(); }
		}]
	});
	miniShop.window.CreatePayment.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.CreatePayment,MODx.Window);
Ext.reg('minishop-window-payment-create',miniShop.window.CreatePayment);