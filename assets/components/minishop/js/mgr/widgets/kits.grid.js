miniShop.grid.Kits = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'minishop-grid-kits'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/goods/kits/getlist'}
		,cls: 'ms-grid'
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,clicksToEdit: 'auto'
		//,preventSaveRefresh: false
		,fields: ['id','pagetitle','parent','resources','url','published','deleted','hidemenu','menu']
		,columns: [
			{header: _('id'), dataIndex: 'id', sortable: true, width: 35}
			,{header: _('ms.warehouse'), dataIndex: 'wid', hidden: true}
			,{header: _('pagetitle'),dataIndex: 'pagetitle', sortable: true, width: 100}
			,{header: _('parent'),dataIndex: 'parent',hidden: true,sortable: true}
			,{header: _('resources'),dataIndex: 'resources'}
		]
		,tbar: [
			{text: _('ms.goods.create'),handler: this.createKit,scope: this}
			,{xtype: 'tbfill'}
			,{xtype: 'minishop-filter-byquery',id: 'minishop-kits-filter-byquery',listeners: {render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}}}
			,{xtype: 'minishop-filter-clear',listeners: {click: {fn: this.FilterClear, scope: this}}}
		]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.editKit(grid, e, row);
			}
		}
		,viewConfig: {
			forceFit:true,
			enableRowBody:true,
			showPreview:true,
			getRowClass : function(rec, ri, p){
				var cls = 'ms-row';
				if (!rec.data.published) cls += ' ms-unpublished';
				if (rec.data.deleted) cls += ' ms-deleted';
				if (rec.data.hidemenu) cls += ' ms-hidemenu';
				return cls;
			}
		}
	});
	miniShop.grid.Kits.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Kits,MODx.grid.Grid,{
	windows: {}
	,FilterClear: function() {
		var s = this.getStore();
		s.baseParams.query = '';
		Ext.getCmp('minishop-kits-filter-byquery').reset();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,FilterByQuery: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,goToGoodsSitePage: function() {
		var url = this.menu.record.url;
		window.open(url);
	}
	,goToGoodsManagerPage: function() {
        location.href = '?a=' + MODx.action['resource/update'] + '&id=' + this.menu.record.id;
	}
	,createKit: function(e) {
		var w = MODx.load({
			xtype: 'minishop-window-createkit'
			,title: _('ms.goods.create')
			,disable_goods: true
			,url: MODx.config.connectors_url+'resource/index.php'
			,action: 'create'
			,gid: -1
			,record: {richtext: 1}
			,listeners: {
				'success':{fn:function() {Ext.getCmp('minishop-grid-kits').store.reload();},scope:this}
				,'show':{fn:function() {this.center();}}
				,'beforesubmit': {fn:function(d) {
					if (d.parent == 0) {
						if (!confirm(_('ms.goods.cat0_confirm'))) {return false;}
					}
				}}
			}
		});
		w.show(e.target,function() {w.setPosition(null,50)},this);
	}
	,editKit: function(btn, e, row) {
		if (typeof(row) != 'undefined') {gid = row.data.id}
		else {gid = this.menu.record.id}
		changed = 0;
		MODx.Ajax.request({
			url: MODx.config.connectors_url+'resource/index.php'
			,params: {
				action: 'get'
				,id: gid
			}
			,listeners: {
				'success': {fn:function(r) {
					var record = r.object;

					var w = MODx.load({
						xtype: 'minishop-window-createkit'
						,title: record.pagetitle
						,record: record
						,disable_goods: false
						,activeTab: 1
						,url: MODx.config.connectors_url+'resource/index.php'
						,action: 'update'
						,gid: gid
						,listeners: {
							hide: {fn:function() {
								if (changed == 1) {
									Ext.getCmp('minishop-grid-kits').store.reload();
								}
								changed = 0;
							}}
						}
					});
					w.setValues(r.object);
					w.show(e.target,function() {w.setPosition(null,50)},this);
				},scope:this}
			}
		});
	}
	,deleteGoods: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.msg.confirm({
			title: _('resource_delete')
			,text: _('ms.kits.delete_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/goods/delete'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		});
	}
	,undeleteGoods: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.Ajax.request({
			url: MODx.config.connectors_url + 'resource/index.php'
			,params: {
				action: 'undelete'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		})
	}
	,publishGoods: function(btn,e) {
		if (!this.menu.record) return false;
		MODx.Ajax.request({
			url: MODx.config.connectors_url + 'resource/index.php'
			,params: {
				action: 'publish'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		})
	}
	,unpublishGoods: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.msg.confirm({
			title: _('resource_unpublish')
			,text: _('resource_unpublish_confirm')
			,url: MODx.config.connectors_url + 'resource/index.php'
			,params: {
				action: 'unpublish'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		});
	}
});
Ext.reg('minishop-grid-kits',miniShop.grid.Kits);


miniShop.window.createKit = function(config) {
	config = config || {};

	this.ident = config.ident || 'qcr'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.goods.create')
		,id: this.ident
		,width: 700
		,modal: true
		,labelAlign: 'left'
		,labelWidth: 150
		,url: MODx.config.connectors_url+'resource/index.php'
		,action: 'create'
		,shadow: false
		,fields: [{
			xtype: 'modx-tabs'
			,activeTab: config.activeTab || 0
			,bodyStyle: { background: 'transparent' }
			,deferredRender: false
			,autoHeight: true
			,items: [{
				title: _('resource')
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
							{xtype: 'hidden',name: 'id',id: 'modx-'+this.ident+'-id',value: config.gid || 0}
							,{xtype: 'textfield',name: 'pagetitle',id: 'modx-'+this.ident+'-pagetitle',fieldLabel: _('pagetitle'),anchor: '100%',allowBlank: false}
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
							{xtype: 'minishop-combo-goodstemplate',id: 'modx-'+this.ident+'-template',baseParams: {action:  'mgr/goods/gettpllist',kits: 1}, fieldLabel: _('template'),editable: false,anchor: '100%',value: miniShop.config.ms_kits_tpls[0]}
							,{xtype: 'minishop-filter-category',id: 'modx-'+this.ident+'-category',baseParams: {action: 'mgr/combo/cats_and_goods',addall: 0,mode: 'kits'}, name: 'parent',fieldLabel: _('parent'),anchor: '100%',hiddenName: 'parent', value: MODx.config['minishop.default_kits_parent']}
							,{xtype: 'textfield',name: 'alias',id: 'modx-'+this.ident+'-alias',fieldLabel: _('alias'),anchor: '100%'}
							,{xtype: 'textfield',name: 'menutitle',id: 'modx-'+this.ident+'-menutitle',fieldLabel: _('resource_menutitle'),anchor: '100%'}
							,{xtype: 'xcheckbox',name: 'published',id: 'modx-'+this.ident+'-published',boxLabel: _('resource_published'),description: _('resource_published_help'),inputValue: 1,checked: MODx.config.publish_default == '1' && config.disable_goods ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'hidemenu',id: 'modx-'+this.ident+'-hidemenu',boxLabel: _('resource_hide_from_menus'),description: _('resource_hide_from_menus_help'),inputValue: 1,checked: MODx.config.hidemenu_default == '1' && config.disable_goods ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'searchable',id: 'modx-'+this.ident+'-searchable',boxLabel: _('resource_searchable'),description: _('resource_searchable_help'),inputValue: 1,checked: MODx.config.search_default == '1' && config.disable_goods  ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'cacheable',id: 'modx-'+this.ident+'-cacheable',boxLabel: _('resource_cacheable'),description: _('resource_cacheable_help'),inputValue: 1,checked: MODx.config.cache_default == '1' && config.disable_goods  ? 1 : 0}
						]
					}]
				},{xtype: config.record.richtext ? 'htmleditor' : 'textarea',name: 'content',id: 'modx-'+this.ident+'-content', fieldLabel: _('content'),anchor: '100%',height: 150}
					,{xtype: 'xcheckbox',name: 'richtext',id: 'modx-'+this.ident+'-richtext',boxLabel: _('resource_richtext'),description: _('resource_richtext_help'),inputValue: 1,checked: MODx.config.richtext_default == '1' && config.disable_goods  ? 1 : 0}
					,{xtype: 'hidden',name: 'class_key',value: 'modDocument'}
					,{xtype: 'hidden',name: 'context_key'}
					,{xtype: 'hidden',name: 'content_type' ,value: 1}
					,{xtype: 'hidden',name: 'content_dispo',value: 0}
					,{xtype: 'hidden',name: 'isfolder' ,value: 0}
				]
			},{
				title: _('ms.goods')
				,items: [{
					xtype: 'minishop-grid-goods-in-kit'
					,disabled: config.disable_goods
					,gid: config.gid
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
			text: config.saveBtnText || _('save_and_close')
			,scope: this
			,handler: function() {changed = 1; this.submit() }
		}]
	});
	miniShop.window.createKit.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.createKit,MODx.Window);
Ext.reg('minishop-window-createkit',miniShop.window.createKit);


miniShop.grid.Kititems = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: this.ident+'-grid-goods-in-kit'
		,url: miniShop.config.connector_url
		,baseParams: {
			action: 'mgr/goods/kits/getgoods'
			,gid: config.gid
		}
		,fields: ['id','gid','pagetitle','parent','url','menu']
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',hidden: true, sortable: false}
			,{header: _('pagetitle'),dataIndex: 'pagetitle',width: 100, sortable: true}
			,{header: _('ms.category'),dataIndex: 'parent',width: 100, sortable: false}
		]
		,tbar: [{
			xtype: 'minishop-combo-goods'
			,id: Ext.id() + '-kits-selectgoods'
			,width: 300
			,listeners: {select: {fn: this.addItem, scope:this}}
		}/*,{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: Ext.id() + '-filter-byquery'
			,width: 150
			,emptyText: _('ms.search')
			,listeners: {
				render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}
			}
		}*/]
	});
	miniShop.grid.Kititems.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Kititems,MODx.grid.Grid, {
	/*
	FilterByQuery: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},*/
	removeItem: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/goods/kits/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		})
	}
	,addItem: function(combo, row, index) {
		if (!row.id || !this.config.gid) return false;

		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/goods/kits/add'
				,rid: this.config.gid
				,gid: row.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		})
	}
	,goToGoodsSitePage: function() {
		var url = this.menu.record.url;
		window.open(url);
	}
	,goToGoodsManagerPage: function() {
        location.href = '?a=' + MODx.action['resource/update'] + '&id=' + this.menu.record.gid;
	}
});
Ext.reg('minishop-grid-goods-in-kit',miniShop.grid.Kititems);
