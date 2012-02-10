miniShop.grid.Goods = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'minishop-grid-goods'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/goods/getlist'
        }
		,save_action: 'mgr/goods/updatefromgrid'
		,autosave: true
        //,plugins: 
		,autoHeight: true
        ,paging: true
        ,remoteSort: true
		,clicksToEdit: 'auto'
		//,preventSaveRefresh: false
		,fields: ['id','pagetitle','parent','wid','article','price','img','remains','url']
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
			,width: 35
		},{
            header: _('ms.warehouse')
            ,dataIndex: 'wid'
            ,hidden: true
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,width: 100
			,sortable: true
        },{
            header: _('parent')
            ,dataIndex: 'parent'
			,hidden: true
        },{
            header: _('ms.article')
            ,dataIndex: 'article'
            ,width: 50
			,sortable: true
        },{
            header: _('ms.price')
            ,dataIndex: 'price'
            ,width: 50
		},{
            header: _('ms.img')
            ,dataIndex: 'img'
            ,width: 50
			,renderer: this.renderImg
		},{
            header: _('ms.remains')
            ,dataIndex: 'remains'
            ,width: 50
		}/*,{
			header: _('ms.change')
			,dataIndex: 'change'
			,width: 50
			,editor: { 
				xtype: 'numberfield'
				,autoShow: true
				,clicksToEdit: 'auto'
				,renderer: true
			}
			
		},*/]
		,tbar: [
			'<strong>' + _('ms.warehouse') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-warehouse'
			,id: 'goods-filter-warehouse'
			,listeners: {
				select: {fn: this.filterByWarehouse, scope:this}
			}
        },{
			xtype: 'tbspacer'
			,width: 10
		},
			'<strong>' + _('ms.category') + ':</strong>&nbsp;'
		,{
			xtype: 'minishop-filter-category'
			,id: 'goods-filter-category'
			,width: 200
			,listeners: {
				'select': {fn: this.filterByCategory, scope:this}
			}
         },{
			xtype: 'tbfill'
		},{
			xtype: 'minishop-filter-byquery'
			,id: 'minishop-goods-filter-byquery'
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
				this.editGoods(grid, e, row);
			}
			//,rowClick: this.startEditChange
			//,afteredit: this.getRowParams
			//,afterAutoSave: this.updateRow
		}
    });
    miniShop.grid.Goods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Goods,MODx.grid.Grid,{
    windows: {}
	
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
	,renderImg: function(img) {
		if (img.length > 0) {
			return '<img src="/'+img+'" height="30" alt="" title="" />';
		}
		else {
			return '';
		}
	}
/*
    ,createOperation: function(btn,e) {
        if (!this.windows.createOperation) {
            this.windows.createOperation = MODx.load({
                xtype: 'minishop-window-operation-create'
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.createOperation.fp.getForm().reset();
		var product = this.menu.record.id;
		var minishop = Ext.getCmp('goods-filter-minishop').getValue();
        this.windows.createOperation.fp.getForm().setValues({product: product, minishop: minishop});
        this.windows.createOperation.show(e.target);
    }
	,startEditChange: function(grid, rowIndex, columnIndex) {
		grid.startEditing(rowIndex, 6);
	}
	,getRowParams: function(e) {
		this.editedRow = e;
		//grid.startEditing(rowIndex, 6);
	}
	,updateRow: function(e) {
		this.editedRow.record.data.change = '';
		this.editedRow.record.data.remains = e.object.remains;
		this.editedRow.record.commit();
	}
*/
    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('ms.goods.change')
            ,handler: this.editGoods
            ,scope: this
        });
        m.push('-');
        m.push({
            text: _('ms.goods.goto_site_page')
            ,handler: this.goToGoodsSitePage
        }); 
        m.push({
            text: _('ms.goods.goto_manager_page')
            ,handler: this.goToGoodsManagerPage
        });
		m.push('-');        
		m.push({
            text: _('ms.goods.delete')
            ,handler: this.deleteGoods
        });
        this.addContextMenuItem(m);
    }

	,goToGoodsSitePage: function() {
		var url = this.menu.record.url;
		window.open(url);
    }	
	,goToGoodsManagerPage: function() {
		var id = this.menu.record.id;
		window.open('/manager/index.php?a=30&id=' + id);
    }
    ,editGoods: function(btn, e, row) {
        if (this.menu.record && this.menu.record.id) {
			gid = this.menu.record.id
			wid = this.menu.record.wid
		}
		else {
			gid = row.id
			wid = row.wid
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
                    var pr = r.object;
                    
                    var w = MODx.load({
                        xtype: 'minishop-window-editgoods'
                        ,record: pr
                        ,listeners: {
                            'success':{fn:function() {
                            },scope:this}
                            ,'hide':{fn:function() {
								if (changed == 1) {
									Ext.getCmp('minishop-grid-goods').store.reload();
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
	
	
    ,deleteGoods: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('ms.goods.delete')
            ,text: _('ms.goods.delete_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/goods/delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) {
					this.refresh();
					//Ext.getCmp('modx-resource-tree').refresh();
				},scope:this}
            }
        });
    }

});
Ext.reg('minishop-grid-goods',miniShop.grid.Goods);















miniShop.window.EditGoods = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('ms.window.editgoods')
        ,id: this.ident
        ,width: 475
        ,url: miniShop.config.connector_url
		,action: 'mgr/warehouse/create'
		,labelAlign: 'left'
		,labelWidth: 150
		,modal: true
        ,url: miniShop.config.connector_url
        ,action: 'mgr/goods/update'
        ,fields: [{
            xtype: 'modx-tabs'
            ,autoHeight: true
            ,deferredRender: false
			,style: 'padding: 0 5px;'
			,bodyStyle: 'padding-top: 10px;'
            ,items: [{
                title: _('ms.properties')
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
						xtype: 'hidden'
						,name: 'wid'
					},{
						xtype: 'textfield'
						,name: 'pagetitle'
						,fieldLabel: _('pagetitle')
						,anchor: '100%'
					},{
						xtype: 'textfield'
						,name: 'longtitle'
						,fieldLabel: _('long_title')
						,anchor: '100%'
					},{
						xtype: 'minishop-filter-category'
						,name: 'parent'
						,fieldLabel: _('ms.category')
						,baseParams: {
							action: 'mgr/goods/getcombo'
							,addall: 0
						}
						,anchor: '100%'
						,hiddenName: 'parent'
					},{
						xtype: 'textfield'
						,name: 'article'
						,fieldLabel: _('ms.article')
					},{
						xtype: 'numberfield'
						,name: 'price'
						,fieldLabel: _('ms.price')
					},{
						xtype: 'modx-combo-browser'
						,name: 'img'
						,fieldLabel: _('ms.img')
						,anchor: '100%'
					},{
						xtype: 'textfield'
						,name: 'remains'
						,fieldLabel: _('ms.remains')
					},{
						xtype: 'textarea'
						,name: 'content'
						,fieldLabel: _('ms.content')
						,anchor: '100%'
						,height: 100
					},{
						xtype: 'checkbox'
						,name: 'duplicate'
						,value: 1
						,fieldLabel: _('ms.goods.duplicate')
						,description: _('ms.goods.duplicate.desc')
					}]
				}]
				// Второй таб
				},{
					title: _('ms.categories')
					,items: [{
						xtype: 'minishop-grid-categories'
						,baseParams: {
							action: 'mgr/goods/getcatlist'
							,gid: gid
						}
					}]
				}
			]
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
            ,handler: function() {changed = 0; this.hide(); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: function() {changed = 1; this.submit() }
        }]
    });
    miniShop.window.EditGoods.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.window.EditGoods,MODx.Window);
Ext.reg('minishop-window-editgoods',miniShop.window.EditGoods);



miniShop.grid.Categories = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        id: this.ident+'-grid-categories'
        ,url: miniShop.config.connector_url
        ,baseParams: {
            action: 'mgr/goods/getcatlist'
        }
		,autosave: true
		//,preventSaveRefresh: false
		//,clicksToEdit: 'auto'
		,save_action: 'mgr/goods/updatefromgrid'
        ,fields: ['cid','gid','name','enabled']
		,pageSize: 10
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('ms.cid')
            ,dataIndex: 'cid'
			,hidden: true
        },{
            header: _('ms.gid')
            ,dataIndex: 'gid'
			,hidden: true
        },{
            header: _('ms.name')
            ,dataIndex: 'name'
            ,width: 150
        },{
			header: _('ms.enabled')
			,dataIndex: 'enabled'
			,width: 60
			,sortable: false
			,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
		}]
		/*
        ,tbar: [{
            text: _('minishop.item_create')
            ,handler: this.createItem
            ,scope: this
        }]
		*/
    });
    miniShop.grid.Categories.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.grid.Categories,MODx.grid.Grid);
Ext.reg('minishop-grid-categories',miniShop.grid.Categories);




