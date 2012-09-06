function renderImg(img) {
	img_lwc = img.toLowerCase();
	if (img.length > 0) {
		if (!/(jpg|jpeg|png|gif|bmp)$/.test(img_lwc)) {return img;}
		else if (/^(http|https|\/)/.test(img_lwc)) {return '<img src="'+img+'" alt="" style="display:block;margin:auto;height:30px;" />'}
		else {return '<img src="/'+img+'" alt="" style="display:block;margin:auto;height:30px;" />'}
	}
	else {return '';}
}
miniShop.grid.Goods = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'minishop-grid-goods'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/goods/getlist'}
		,cls: 'ms-grid'
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,clicksToEdit: 'auto'
		//,preventSaveRefresh: false
		,fields: ['id','pagetitle','parent','wid','article','price','weight','img','remains','reserved','url','published','deleted','hidemenu','menu']
		,columns: [
			{header: _('id'), dataIndex: 'id', sortable: true, width: 35}
			,{header: _('ms.warehouse'), dataIndex: 'wid', hidden: true}
			,{header: _('pagetitle'),dataIndex: 'pagetitle', sortable: true, width: 100}
			,{header: _('parent'),dataIndex: 'parent',hidden: true,sortable: true}
			,{header: _('ms.article'),dataIndex: 'article',sortable: true, width: 50}
			,{header: _('ms.price'),dataIndex: 'price',sortable: true, width: 50}
			,{header: _('ms.weight'),dataIndex: 'weight',sortable: true, width: 50}
			,{header: _('ms.img'),dataIndex: 'img',renderer: renderImg,sortable: true,width: 50}
			,{header: _('ms.remains'),dataIndex: 'remains',sortable: true, width: 50}
			,{header: _('ms.reserved'),dataIndex: 'reserved',sortable: true, width: 50}
		]
		,tbar: [
			{text: _('ms.goods.create'),handler: this.createGoods,scope: this}
			,{text: '',menu: this.getGoodsMenu()}
			,{xtype: 'tbspacer',width: 30},
			'<strong>' + _('ms.warehouse') + ':</strong>&nbsp;'
			,{xtype: 'minishop-filter-warehouse',id: 'goods-filter-warehouse',listeners: {select: {fn: this.filterByWarehouse, scope:this}}}
			,{xtype: 'tbspacer',width: 10},
			'<strong>' + _('ms.category') + ':</strong>&nbsp;'
			,{xtype: 'minishop-filter-category',id: 'goods-filter-category',width: 200,listeners: {'select': {fn: this.filterByCategory, scope:this}}}
			,{xtype: 'tbfill'}
			,{xtype: 'minishop-filter-byquery',id: 'minishop-goods-filter-byquery',listeners: {render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}}}
			,{xtype: 'minishop-filter-clear',listeners: {click: {fn: this.FilterClear, scope: this}}}
		]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.editGoods(grid, e, row);
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
	miniShop.grid.Goods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Goods,MODx.grid.Grid,{
	windows: {}
	,getGoodsMenu: function() {
		var m = [];
		m.push({
			text: _('ms.import')
			,handler: startImport
			,scope: this
		}/*,{
			text: _('ms.export')
			,handler: startExport
			,scope: this
		}*/);
		return m;
	}
	,FilterClear: function() {
		var s = this.getStore();
		s.baseParams.query = '';
		Ext.getCmp('minishop-goods-filter-byquery').reset();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,FilterByQuery: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,filterByWarehouse: function(cb) {
		this.getStore().baseParams['warehouse'] = cb.value;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}    
	,filterByCategory: function(cb) {
		this.getStore().baseParams['category'] = cb.value;
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
	,goToGoodsSitePage: function() {
		var url = this.menu.record.url;
		window.open(url);
	}
	,goToGoodsManagerPage: function() {
		var id = this.menu.record.id;
		window.open('/manager/index.php?a=30&id=' + id);
	}
	,createGoods: function(e) {
		gid = 0;
		var w = MODx.load({
			xtype: 'minishop-window-creategoods'
			,title: _('ms.goods.create')
			,disable_categories: true
			,action: 'mgr/goods/create'
			,record: {richtext: 1}
			,listeners: {
				'success':{fn:function() {Ext.getCmp('minishop-grid-goods').store.reload();},scope:this}
				,'hide':{fn:function() {this.getEl().remove();}}
				,'beforesubmit': {fn:function(d) {
					if (d.parent == 0) {
						if (!confirm(_('ms.goods.cat0_confirm'))) {return false;}
					}
				}}
			}
		});
		w.show(e.target,function() {w.setPosition(null,50)},this);
	}
	,editGoods: function(btn, e, row) {
		if (typeof(row) != 'undefined') {
			gid = row.data.id
			wid = row.data.wid
		}
		else {
			gid = this.menu.record.id
			wid = this.menu.record.wid
		}
		changed = 0;
		MODx.Ajax.request({
			url: miniShop.config.connector_url
			,params: {
				action: 'mgr/goods/get'
				,id: gid
				,wid: wid
			}
			,listeners: {
				'success': {fn:function(r) {
					var record = r.object;
					
					var w = MODx.load({
						xtype: 'minishop-window-creategoods'
						,title: record.pagetitle
						,record: record
						,disable_categories: false
						,activeTab: 1
						,action: 'mgr/goods/update'
						,listeners: {
							//success:{fn:function() {},scope:this}
							hide: {fn:function() {
								if (changed == 1) {
									Ext.getCmp('minishop-grid-goods').store.reload();
								}
								changed = 0;
								this.getEl().remove()
							}}
						}
					});
					w.setValues(r.object);
					w.show(e.target,function() {w.setPosition(null,50)},this);
				},scope:this}
			}
		});
	}
	,duplicateGoods: function(btn,e) {
		MODx.msg.confirm({
			title: _('ms.duplicate')
			,text: _('ms.duplicate_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/goods/duplicate'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
				},scope:this}
			}
		});
	}
	,deleteGoods: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('resource_delete')
			,text: _('ms.goods.delete_confirm')
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
Ext.reg('minishop-grid-goods',miniShop.grid.Goods);


miniShop.window.createGoods = function(config) {
	config = config || {};

	this.ident = config.ident || 'qcr'+Ext.id();
	Ext.applyIf(config,{
		title: _('ms.goods.create')
		,id: this.ident
		,width: 700
		,modal: true
		,labelAlign: 'left'
		,labelWidth: 150
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/create'
		,shadow: false
		,fields: [{
			xtype: 'modx-tabs'
			,activeTab: config.activeTab || 0
			,bodyStyle: { background: 'transparent' }
			,deferredRender: false
			,autoHeight: true
			,stateful: true
			,stateId: 'ms-tabs-goods'
			,stateEvents: ['tabchange']
			,getState:function() {
				return { activeTab:this.items.indexOf(this.getActiveTab()) };
			}
			,items: [{
				id: 'modx-'+this.ident+'-resource'
				,title: _('resource')
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
							{xtype: 'textfield',name: 'pagetitle',id: 'modx-'+this.ident+'-pagetitle',fieldLabel: _('pagetitle'),anchor: '100%',allowBlank: false}
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
							{xtype: 'minishop-combo-goodstemplate',id: 'modx-'+this.ident+'-template',fieldLabel: _('template'),editable: false,anchor: '100%',value: miniShop.config.ms_goods_tpls[0]}
							,{xtype: 'minishop-filter-category',id: 'modx-'+this.ident+'-category', name: 'parent',fieldLabel: _('ms.category'),baseParams: {action: 'mgr/combo/cats_and_goods',addall: 0},anchor: '100%',hiddenName: 'parent'}
							,{xtype: 'textfield',name: 'alias',id: 'modx-'+this.ident+'-alias',fieldLabel: _('alias'),anchor: '100%'}
							,{xtype: 'textfield',name: 'menutitle',id: 'modx-'+this.ident+'-menutitle',fieldLabel: _('resource_menutitle'),anchor: '100%'}
							,{xtype: 'xcheckbox',name: 'published',id: 'modx-'+this.ident+'-published',boxLabel: _('resource_published'),description: _('resource_published_help'),inputValue: 1,checked: MODx.config.publish_default == '1' && config.disable_categories ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'hidemenu',id: 'modx-'+this.ident+'-hidemenu',boxLabel: _('resource_hide_from_menus'),description: _('resource_hide_from_menus_help'),inputValue: 1,checked: MODx.config.hidemenu_default == '1' && config.disable_categories ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'searchable',id: 'modx-'+this.ident+'-searchable',boxLabel: _('resource_searchable'),description: _('resource_searchable_help'),inputValue: 1,checked: MODx.config.search_default == '1' && config.disable_categories  ? 1 : 0}
							,{xtype: 'xcheckbox',name: 'cacheable',id: 'modx-'+this.ident+'-cacheable',boxLabel: _('resource_cacheable'),description: _('resource_cacheable_help'),inputValue: 1,checked: MODx.config.cache_default == '1' && config.disable_categories  ? 1 : 0}
						]
					}]
				},{xtype: config.record.richtext ? 'htmleditor' : 'textarea',name: 'content',id: 'modx-'+this.ident+'-content', fieldLabel: _('content'),anchor: '100%',height: 150}
					,{xtype: 'xcheckbox',name: 'richtext',id: 'modx-'+this.ident+'-richtext',boxLabel: _('resource_richtext'),description: _('resource_richtext_help'),inputValue: 1,checked: MODx.config.richtext_default == '1' && config.disable_categories  ? 1 : 0}
					,{xtype: 'hidden',name: 'class_key',value: 'modDocument'}
					,{xtype: 'hidden',name: 'context_key'}
					,{xtype: 'hidden',name: 'content_type' ,value: 1}
					,{xtype: 'hidden',name: 'content_dispo',value: 0}
					,{xtype: 'hidden',name: 'isfolder' ,value: 0}
				]
			},{
				id: 'modx-'+this.ident+'-properties'
				,title: _('ms.properties')
				,layout: 'form'
				,cls: 'modx-panel'
				,autoHeight: true
				,forceLayout: true
				,labelAlign: 'left'
				,labelWidth: 200
				,defaults: {autoHeight: true ,border: false}
				,style: 'background: transparent;'
				,bodyStyle: { background: 'transparent', padding: '10px' }
				,items: [
					{xtype: 'hidden',name: 'id'}
					,{xtype: 'hidden',name: 'wid'}
					,{xtype: 'textfield',name: 'article',fieldLabel: _('ms.article')}
					,{xtype: 'numberfield',name: 'price',fieldLabel: _('ms.price')}
					,{xtype: 'numberfield',name: 'weight',decimalPrecision: 3, fieldLabel: _('ms.weight')}
					,{xtype: 'ms-combo-browser', openTo: config.record.img, name: 'img',fieldLabel: _('ms.img'),anchor: '100%'}
					,{xtype: 'numberfield',name: 'remains',fieldLabel: _('ms.remains')}
					,{xtype: 'textfield',name: 'reserved',disabled: true,fieldLabel: _('ms.reserved')}
					,{xtype: 'ms-superbox-tags', name: 'tags[]', value: config.record.tags, fieldLabel: _('ms.tags')}
					,{xtype: 'textfield',name: 'add1',fieldLabel: _('ms.goods.add1'),anchor: '100%'}
					,{xtype: 'textfield',name: 'add2',fieldLabel: _('ms.goods.add2'),anchor: '100%'}
					,{xtype: 'textarea',name: 'add3',fieldLabel: _('ms.goods.add3'),autoHeight: false,anchor: '100%',height: 100}
					,{xtype: 'checkbox',name: 'duplicate',value: 1,style: 'padding: 10px;',fieldLabel: _('ms.goods.duplicate'),description: _('ms.goods.duplicate.desc')}
				]
			},{
				id: 'modx-'+this.ident+'-tvs'
				,title: 'TVs'
				,items: [{
					xtype: 'minishop-grid-tvs'
					,disabled: config.disable_categories
					,baseParams: {
						action: 'mgr/goods/tv/getlist'
						,gid: gid
					}
				}]
			},{
				id: 'modx-'+this.ident+'-gallery'
				,title: _('ms.gallery')
				,items: [{
					xtype: 'minishop-grid-gallery'
					,disabled: config.disable_categories
					,baseParams: {
						action: 'mgr/goods/gallery/getlist'
						,gid: gid
					}
					,gid: gid
				}]
			},{
				id: 'modx-'+this.ident+'-categories'
				,title: _('ms.categories')
				,items: [{
					xtype: 'minishop-grid-categories'
					,disabled: config.disable_categories
					,baseParams: {
						action: 'mgr/goods/getcatlist'
						,gid: gid
					}

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
			xtype: 'tbfill'
		},{
			text: config.saveBtnText || _('save_and_close')
			,scope: this
			,handler: function() {changed = 1; this.submit() }
		}]
	});
	miniShop.window.createGoods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.createGoods,MODx.Window);
Ext.reg('minishop-window-creategoods',miniShop.window.createGoods);


miniShop.grid.Categories = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: this.ident+'-grid-categories'
		,url: miniShop.config.connector_url
		,baseParams: {action: 'mgr/goods/getcatlist'}
		,autosave: true
		,save_action: 'mgr/goods/updatefromgrid'
		,fields: ['id','gid','pagetitle','enabled']
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('ms.cid'),dataIndex: 'id',hidden: true}
			,{header: _('ms.gid'),dataIndex: 'gid',hidden: true}
			,{header: _('ms.name'),dataIndex: 'pagetitle',width: 500,sortable: true}
			,{header: _('ms.enabled'),dataIndex: 'enabled',width: 100,editor: { xtype: 'combo-boolean', renderer: 'boolean' }}
		]
		,tbar: [{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: Ext.id() + '-filter-byquery'
			,width: 150
			,emptyText: _('ms.search')
			,listeners: {
				render: { fn: function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER, function() {this.FilterByQuery(tf);}, this);},scope: this}
			}
		}]
	});
	miniShop.grid.Categories.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Categories,MODx.grid.Grid, {
	FilterByQuery: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('minishop-grid-categories',miniShop.grid.Categories);


miniShop.grid.TVs = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: this.ident+'-grid-tvs'
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/tv/getlist'
		,fields: ['id','name','resourceId','caption','value','intro','input_properties','type']
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',hidden: true,sortable: false}
			,{header: _('name'),dataIndex: 'name',sortable: false,width: 50}
			,{header: _('caption'),dataIndex: 'caption',sortable: false,width: 100}
			,{header: _('value'),dataIndex: 'intro',sortable: false,width: 150}
		]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateTV(grid, e, row);
			}
		}
	});
	miniShop.grid.TVs.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.TVs,MODx.grid.Grid, {
	windows: {}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.tv.update')
			,handler: this.updateTV
		});
		//m.push('-');
		this.addContextMenuItem(m);
	}
	,updateTV: function(btn,e,row) {
		if (typeof(row) != 'undefined') {
			var record = row.data;
		}
		else {
			var record = this.menu.record;
		}
		var w = MODx.load({
			xtype: 'minishop-window-goods-tv'
			,props: record
			,title: record.name
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
				,'hide': {fn:function() { this.destroy(); }}
			}
		});
		w.fp.getForm().reset();

		var vf = {fieldLabel: _('value'),name: 'value',id: 'minishop-'+this.ident+'-value', anchor: '100%'};
		var def = 0;
		switch (record.type) {
			case 'number': vf.xtype = 'numberfield'; break;
			case 'text': vf.xtype = 'textfield'; break;
			case 'image': vf.xtype = 'ms-combo-browser'; break;
			case 'file': vf.xtype = 'ms-combo-browser'; break;
			case 'date': vf.xtype = 'xdatetime'; break;
			case 'text': vf.xtype = 'textarea'; break;
			case 'checkbox': vf.xtype = 'textfield'; break;
			case 'radio': vf.xtype = 'textfield'; break;
			case 'option': vf.xtype = 'textfield'; break;
			case 'richtext': vf.xtype = 'htmleditor'; vf.height = 400; break;
			case 'textarea': vf.xtype = 'textarea'; vf.height = 400; break;
			default: vf.xtype = 'textarea'; vf.height = 400; var def = 1;
		}
		if (def != 1) {
			//console.log(vf)
			Ext.applyIf(vf,record.input_properties);
		}

		w.fp.add(vf);
		w.fp.getForm().setValues(record);
		w.show(e.target,function() {w.setPosition(null,100)},this);
	}

});
Ext.reg('minishop-grid-tvs',miniShop.grid.TVs);


miniShop.window.updateTV = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.orderedgoods.add')
		,id: this.ident
		,width: 700
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/tv/update'
		,labelAlign: 'top'
		,autoHeight: true
		,fields: [{
			items: [{
				layout: 'form'
				,cls: 'modx-panel'
				,bodyStyle: { background: 'transparent', padding: '10px' }
				,items: [{
					layout: 'column'
					,border: false
					,items: [{
						columnWidth: .5
						,border: false
						,layout: 'form'
						,items: [
							{xtype: 'hidden',name: 'id',id: 'minishop-'+this.ident+'-id'}
							,{xtype: 'hidden',name: 'resourceId',id: 'minishop-'+this.ident+'-resourceId'}
							,{xtype: 'hidden',name: 'type',id: 'minishop-'+this.ident+'-type'}
							,{xtype: 'displayfield',fieldLabel: _('name'),name: 'name',id: 'minishop-'+this.ident+'-name'}
						]
					},{
						columnWidth: .5
						,border: false
						,layout: 'form'
						,items: [
							{xtype: 'displayfield',fieldLabel: _('caption'),name: 'caption',id: 'minishop-'+this.ident+'-caption'}
						]
					}]
				}]
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
			,handler: function() { this.hide();}
		},{
			text: _('save_and_close')
			,scope: this
			,handler: function() { this.submit();}
		}]
	});
	miniShop.window.updateTV.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.updateTV,MODx.Window);
Ext.reg('minishop-window-goods-tv',miniShop.window.updateTV);


miniShop.grid.Gallery = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: this.ident+'-grid-gallery'
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/gallery/getlist'
		,fields: ['id','gid','name','description','file','fileorder']
		,pageSize: Math.round(MODx.config.default_per_page / 4)
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',hidden: true,sortable: true}
			,{header: _('gid'),dataIndex: 'gid',hidden: true,sortable: true}
			,{header: _('name'),dataIndex: 'name',sortable: true,width: 100}
			,{header: _('description'),dataIndex: 'description',width: 100}
			,{header: _('ms.file'),dataIndex: 'file',sortable: true, hidden: true}
			,{header: _('ms.file'),dataIndex: 'file',renderer: renderImg, width: 80}
		]
		,tbar: [{
			text: _('ms.gallery.create')
			,handler: this.createImage
			,scope: this
		},{
			xtype: 'tbfill'
		},{
			text: _('ms.gallery.load')
			,handler: this.loadImages
			,scope: this
		}]
		,plugins: [new Ext.ux.dd.GridDragDropRowOrder({
			listeners: {
				'afterrowmove': {
					fn: function(drag, old_order, new_order, row) {
						var row = row[0];
						var grid = drag.grid;
						var el = Ext.get(this.ident+'-grid-gallery');
						el.mask(_('loading'),'x-mask-loading')
						MODx.Ajax.request({
							url: miniShop.config.connector_url
							,params: {
								action: 'mgr/goods/gallery/sort'
								,id: row.data.id
								,gid: row.data.gid
								,new_order: new_order
								,old_order: old_order
							}
							,listeners: {
								'success': {fn:function(r) {
									el.unmask();
									grid.refresh();
								},scope:grid}
								,'failure': {fn:function(r) {
									el.unmask();
								},scope:grid}
							}
						})
					}
					,scope: this
				}
			}
		})]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateImage(grid, e, row);
			}
		}
	});
	miniShop.grid.Gallery.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Gallery,MODx.grid.Grid, {
	windows: {}
	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ms.gallery.update')
			,handler: this.updateImage
		});
		m.push('-');
		m.push({
			text: _('ms.gallery.remove')
			,handler: this.removeImage
		});
		this.addContextMenuItem(m);
	}
	,createImage: function(e) {
		var w = MODx.load({
			xtype: 'minishop-window-goods-gallery'
			,title: _('ms.gallery.create')
			,baseParams: {
				action: 'mgr/goods/gallery/create'
				,gid: this.config.gid
			}
			,listeners: {
				'success':{fn:function() {this.refresh();},scope:this}
				,'show':{fn:function() {this.center();}}}
		});
		w.show(e.target,function() {w.setPosition(null,100)},this);
	}
	,loadImages: function(e) {
		var w = MODx.load({
			xtype: 'minishop-window-goods-loadgallery'
			,title: _('ms.gallery.load')
			,baseParams: {
				action: 'mgr/goods/gallery/load'
				,gid: this.config.gid
			}
			,listeners: {
				'success':{fn:function() {this.refresh();},scope:this}
				,'show':{fn:function() {this.center();}}}
		});
		w.show(e.target,function() {w.setPosition(null,100)},this);
	}
	,updateImage: function(btn,e,row) {
		if (typeof(row) != 'undefined') {
			var record = row.data;
		}
		else {
			var record = this.menu.record;
		}
		w = MODx.load({
			xtype: 'minishop-window-goods-gallery'
			,title: _('ms.gallery.update')
			,openTo: record.file
			,listeners: {
				'success': {fn:function() { this.refresh(); },scope:this}
				,'hide': {fn:function() { this.destroy(); }}
			}
		});
		w.fp.getForm().reset();
		w.fp.getForm().setValues(record);
		w.show(e.target,function() {w.setPosition(null,100)},this);
	}
	,removeImage: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ms.gallery.gallery')
			,text: _('ms.gallery.remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/goods/gallery/remove'
				,id: this.menu.record.id
			}
			,listeners: {'success': {fn:function(r) {this.refresh();},scope:this}}
		});
	}

});
Ext.reg('minishop-grid-gallery',miniShop.grid.Gallery);


miniShop.window.updateImage = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.gallery.add')
		,id: this.ident
		,width: 600
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/gallery/update'
		,labelAlign: 'left'
		,labelWidth: 150
		,height: 150
		,autoHeight: true
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'minishop-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('name'),name: 'name',id: 'minishop-'+this.ident+'-name',anchor: '90%'}
			,{xtype: 'textarea',fieldLabel: _('description'),name: 'description',id: 'minishop-'+this.ident+'-description',anchor: '90%'}
			,{xtype: 'ms-combo-browser', openTo: config.openTo, fieldLabel: _('ms.file'),name: 'file',id: 'minishop-'+this.ident+'-file',allowBlank: false,anchor: '90%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
		,buttons: [{text: _('close'),scope: this,handler: function() { this.hide();}},{text: _('save_and_close'),scope: this,handler: function() { this.submit();}}]
	});
	miniShop.window.updateImage.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.updateImage,MODx.Window);
Ext.reg('minishop-window-goods-gallery',miniShop.window.updateImage);

miniShop.window.loadImages = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();

	Ext.applyIf(config,{
		title: _('ms.gallery.add')
		,id: this.ident
		,width: 600
		,url: miniShop.config.connector_url
		,action: 'mgr/goods/gallery/load'
		,labelAlign: 'left'
		,labelWidth: 100
		,height: 150
		,bodyStyle: 'padding: 5px 10px;'
		,autoHeight: true
		//,html: _('ms.gallery.load_description')
		,fields: [
			{xtype: 'ms-combo-browser',fieldLabel: _('ms.dir'),name: 'dir',id: 'minishop-'+this.ident+'-dir',allowBlank: false,anchor: '99%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
		,buttons: [{text: _('close'),scope: this,handler: function() { this.hide();}},{text: _('save_and_close'),scope: this,handler: function() { this.submit();}}]
	});
	miniShop.window.loadImages.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.loadImages,MODx.Window);
Ext.reg('minishop-window-goods-loadgallery',miniShop.window.loadImages);