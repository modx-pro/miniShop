miniShop.grid.Status = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'minishop-grid-status'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/status/getlist'}
		,fields: ['id','name','color','email2user','email2manager','subject2user','body2user','subject2manager','body2manager']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',sortable: true,width: 50}
			,{header: _('ms.status'),dataIndex: 'name',sortable: true,width: 200}
			,{header: _('ms.color'),dataIndex: 'color',renderer: this.renderColor,width: 50}
			,{header: _('ms.email2user'),dataIndex: 'email2user',sortable: true,renderer: this.renderBoolean,width: 50}
			,{header: _('ms.email2manager'),dataIndex: 'email2manager',sortable: true,renderer: this.renderBoolean,width: 50}
		]
		,tbar: [{
			text: _('ms.status.create')
			,handler: this.createStatus
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateStatus(grid, e, row);
			}
		}
	});
	miniShop.grid.Status.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Status,MODx.grid.Grid,{
	windows: {}

	,renderColor: function(value) {
		return '<div style="width: 30px; height: 20px; border-radius: 3px; background: #' + value + '">&nbsp;</div>'
	}	
	,renderBoolean: function(value) {
		if (value == 1) {return _('yes');}
		else {return _('no');}
	}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.status.update')
			,handler: this.updateStatus
		});
		m.push('-');
		m.push({
			text: _('ms.status.remove')
			,handler: this.removeStatus
		});
		this.addContextMenuItem(m);
	}

	,createStatus: function(btn,e) {
		this.windows.createStatus = MODx.load({
			xtype: 'minishop-window-status-create'
			,title: _('ms.status.create')
			,action: 'mgr/status/create'
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
				,hide: {fn:function() { this.destroy(); }}
			}
		});
		this.windows.createStatus.show(e.target);
	}
	,updateStatus: function(btn,e,row) {
		if (typeof(row) != 'undefined') {
			var record = row.data;
		}
		else {
			var record = this.menu.record;
		}
		this.windows.createStatus = MODx.load({
			xtype: 'minishop-window-status-create'
			,action: 'mgr/status/update'
			,title: record.name
			,record: record
			,listeners: {
				success: {fn:function() { this.refresh(); },scope:this}
				,hide: {fn:function() { this.destroy(); }}
			}
		});
		this.windows.createStatus.fp.getForm().setValues(record);
		this.windows.createStatus.show(e.target);
	}
	,removeStatus: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ms.status.remove')
			,text: _('ms.status.remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/status/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
});
Ext.reg('minishop-grid-statuses',miniShop.grid.Status);


miniShop.window.CreateStatus = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.status.create')
		,id: this.ident
		,height: 150
		,width: 475
		,labelAlign: 'left'
		,labelWidth: 200
		,url: miniShop.config.connector_url
		,action: 'mgr/status/create'
		,modal: true
		,fields: [{
			xtype: 'hidden'
			,name: 'id'
		},{
			xtype: 'hidden'
			,name: 'color'
			,id: 'minishop-newstatus-color'
		},{
			xtype: 'textfield'
			,fieldLabel: _('ms.statusname')
			,name: 'name'
			,allowBlank: false
			,id: 'minishop-'+this.ident+'-name'
			,width: 200
		},{
			xtype: 'colorpalette'
			,fieldLabel: _('ms.color')
			,width: 200
			,listeners: {
				select: function(palette, setColor) {
					Ext.getCmp('minishop-newstatus-color').setValue(setColor)
				}
				,beforerender: function(palette) {
					var color = Ext.getCmp('minishop-newstatus-color').value;
					if (color != 'undefined') {
						palette.value = color;
					}
				}
			}
		},{
			xtype: 'checkbox'
			,fieldLabel: _('ms.email2user')
			,name: 'email2user'
			,id: 'status-email2user'
			,listeners: {
				check: {fn: function(r) { this.hideStatusFields('user');},scope:this }
				,afterrender: {fn: function(r) { this.hideStatusFields('user');},scope:this }
			}
		},{
			xtype: 'textfield'
			,fieldLabel: _('ms.subject2user')
			,name: 'subject2user'
			,id: 'status-subject2user'
			,anchor: '100%'
		},{
			xtype: 'minishop-combo-chunk'
			,fieldLabel: _('ms.body2user')
			,name: 'body2user'
			,hiddenName: 'body2user'
			,id: 'status-body2user'
			,width: 240
		},{
			xtype: 'checkbox'
			,fieldLabel: _('ms.email2manager')
			,name: 'email2manager'
			,id: 'status-email2manager'
			,listeners: {
				check: {fn: function(r) { this.hideStatusFields('manager');},scope:this }
				,afterrender: {fn: function(r) { this.hideStatusFields('manager');},scope:this }
			}
		},{
			xtype: 'textfield'
			,fieldLabel: _('ms.subject2manager')
			,name: 'subject2manager'
			,id: 'status-subject2manager'
			,anchor: '100%'
		},{
			xtype: 'minishop-combo-chunk'
			,fieldLabel: _('ms.body2manager')
			,name: 'body2manager'
			,hiddenName: 'body2manager'
			,id: 'status-body2manager'
			,width: 240
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
	miniShop.window.CreateStatus.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.CreateStatus,MODx.Window,{
    hideStatusFields: function(v) {
		var el = Ext.getCmp('status-email2'+v);
		if (el.checked) {
			Ext.getCmp('status-subject2'+v).enable().show();
			Ext.getCmp('status-body2'+v).enable().show();
		}
		else {
			Ext.getCmp('status-subject2'+v).hide().disable();
			Ext.getCmp('status-body2'+v).hide().disable();
		}
	}
});
Ext.reg('minishop-window-status-create',miniShop.window.CreateStatus);