function startImport(e) {
	var w = MODx.load({
		xtype: 'minishop-window-import'
	});
	w.fp.getForm().reset().setValues({'offset':0});
	w.show(e.target,function() {w.setPosition(null,50)},this);
}

miniShop.window.Import = function(config) {
	config = config || {};
	this.ident = config.ident || 'meceitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.import')
		,id: this.ident
		,width: 600
		,modal: true
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/import-export/import/import'
		,bodyStyle: 'padding: 5px 10px;'
		,shadow: false
		,autoHeight: true
		,html: '<p style="">'+_('ms.import.intro_msg')+'</p>'
		,layout: 'form'
		,fields: [{
			layout: 'column'
			,border: false
			,items: [{
				columnWidth: .5
				,border: false
				,layout: 'form'
				,labelAlign: 'top'
				,items: [{
					xtype: 'minishop-combo-goodstemplate'
					,allowBlank: false
					,anchor: '100%'
				},{
					xtype: 'modx-combo-browser'
					,name: 'file' 
					,anchor: '100%'
					,emptyText: _('ms.import.select_file')
					,value: ''
					,allowBlank: false
					,hideFiles: true
					,allowedFileTypes: 'csv'
					,editable: false
					,listeners: {
						'select': {fn: function(data) {
							var grid = Ext.getCmp('ms-grid-import')
							grid.enable().show().selectFile(data);
						}, scope: this}
					}
				},{
						xtype: 'numberfield'
						,id: 'import-offset'
						,name: 'offset'
						,allowDecimals: false
						,allowNegative: false
						,inputValue: 0
						,anchor: '100%'
						,fieldLabel: _('ms.import.offset')
						//,allowBlank: false
				}]
			},{
				columnWidth: .5
				,border: false
				,layout: 'form'
				,labelAlign: 'top'
				,items: [{
					xtype: 'minishop-filter-category'
					,name: 'category'
					,anchor: '100%'
					,emptyText: _('ms.import.select_category')
					,value: ''
					,allowBlank: false
					,editable: true
					,baseParams: {
						action: 'mgr/combo/cats_and_goods'
						,addall: 0
					}
				},{
					xtype: 'radiogroup'
					,fieldLabel: _('ms.import.select_mode')
					,allowBlank: false
					,columns: 1
					,items: [
						{boxLabel: _('ms.import.mode_add'),name: 'mode',inputValue: 'add', checked: true},
						{boxLabel: _('ms.import.mode_update'),name: 'mode', inputValue: 'update'},
					]
				},{
					xtype: 'xcheckbox'
					,id: 'import-purge'
					,boxLabel: _('ms.import.category_purge')
					,name: 'purge'
					,inputValue: 1
					,checked: false
				}]
			}]
		},{
			xtype: 'minishop-grid-import'
			,bodyStyle: 'margin: 10px 0'
		}
		]
		//,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
		,listeners: {
			'success': {fn:function(result) {
				this.enable().checkImport(result)
			}}
			,'beforeSubmit': {fn:function(form) {
				if (form.purge != 0) {
					if (!confirm(_('ms.import.purge_confirm'))) {return false;}
				}
				this.disable();
				
			}}
			,'failure': {fn:function() {
				this.enable();
			}}
			,'hide': {fn:function() {
				this.destroy();
			}}
		}
		,buttons: [{
			text: _('close')
			,scope: this
			,handler: function() {
				this.close();
			}
		},{
			text: _('ms.import.btn_import')
			,scope: this
			,handler: function() {
				this.submit(false);
			}
		}]
	});
	miniShop.window.Import.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.Import,MODx.Window, {
	
	checkImport: function(result) {
		result = result.a.result;
		if (result.message != 'ok') {
			Ext.getCmp('import-offset').setValue(result.message);
			Ext.getCmp('import-purge').reset();
			this.submit(false)
		}
		else {
			this.close();
		}
		Ext.getCmp('minishop-grid-goods').refresh();
	}
	
});
Ext.reg('minishop-window-import',miniShop.window.Import);




miniShop.grid.Import = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: 'ms-grid-import'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/goods/import-export/import/getlist'}
		,autosave: true
		,save_action: 'mgr/goods/import-export/import/updatefromgrid'
		,fields: ['index','src','dst']
		,pageSize: Math.round(MODx.config.default_per_page / 4)
		,autoHeight: true
		,paging: true
		,clicksToEdit: 'auto'
		,columns: [
			{header: _('ms.import.src'),dataIndex: 'src'}
			,{header: _('ms.import.dst'),dataIndex: 'dst',editor: {xtype: 'ms-combo-importtypes'}}
		]
		,hidden: true
		,disabled: true
	});
	miniShop.grid.Import.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Import,MODx.grid.Grid,{

	selectFile: function(data) {
		var s = this.getStore();
		s.baseParams.file = data.pathname;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('minishop-grid-import',miniShop.grid.Import);











/*
function startExport(e) {
	this.windows.Export = MODx.load({
		xtype: 'minishop-window-export'
		,listeners: {
			'success': {fn:function() { this.refresh(); },scope:this}
			,'hide': {fn:function() { this.destroy(); }}
		}
	});
	this.windows.Export.fp.getForm().reset();
	//this.windows.Import.fp.getForm().setValues(record);
	this.windows.Export.show(e.target);
}

miniShop.window.Export = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.gallery.add')
		,id: this.ident
		,title: _('ms.export')
		,width: 600
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/import-export/export'
		,labelAlign: 'left'
		,labelWidth: 150
		,height: 150
		,autoHeight: true
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'minishop-'+this.ident+'-id'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
		,buttons: [{text: _('close'),scope: this,handler: function() { this.hide();}},{text: _('save_and_close'),scope: this,handler: function() { this.submit();}}]
	});
	miniShop.window.Export.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.Export,MODx.Window);
Ext.reg('minishop-window-export',miniShop.window.Export);
*/


// Комбобокс типов полей импорта
miniShop.combo.ImportTypes = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'field'
		,hiddenName: 'field'
		,displayField: 'field'
		,valueField: 'field'
		,editable: false
		,fields: ['field']
		,pageSize: 10
		,emptyText: _('ms.file')
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'mgr/combo/import_types'
		}
	});
	miniShop.combo.ImportTypes.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.combo.ImportTypes,MODx.combo.ComboBox);
Ext.reg('ms-combo-importtypes',miniShop.combo.ImportTypes);
///////////////////////////////////////