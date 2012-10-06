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
        ,baseParams: {
            action: 'mgr/orders/getlist'
        }
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
            ,listeners: {
                select: {
                    fn: this.filterByWarehouse
                    ,scope:this
                }
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
    miniShop.grid.Orders.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.grid.Orders, MODx.grid.Grid, {
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
                    w.show(e.target,function() {w.setPosition(null,50)},this);
                },scope:this}
            }
        });
    }
});
Ext.reg('minishop-grid-orders',miniShop.grid.Orders);



// History of changing the order
miniShop.grid.Log = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        expandOnDblClick: false
        ,tpl : new Ext.Template('<p class="desc">{comment}</p>')
        ,renderer : function(v, p, record){return record.data.comment != null && record.data.comment != '' ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';}
    });
    Ext.applyIf(config,{
        id: this.ident+'-grid-log'
        ,url: miniShop.config.connector_url
        ,baseParams: {action: 'mgr/log/getlist',type: 'status',operation: 'change'}
        ,fields: ['oid','iid','type','old','new','name','uid','username','ip','timestamp','comment']
        ,pageSize: Math.round(MODx.config.default_per_page / 2)
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
        ,fields: ['id','gid','oid','name','article','num','price','weight','sum','data','data_view','url']
        ,pageSize: Math.round(MODx.config.default_per_page / 2)
        ,autoHeight: true
        ,paging: true
        ,plugins: this.exp
        ,remoteSort: true
        ,columns: [this.exp
            ,{header: _('id'),dataIndex: 'id',hidden: true,sortable: true,width: 35}
            ,{header: _('ms.gid'),dataIndex: 'gid',hidden: true,sortable: true,width: 35}
            ,{header: _('ms.goods.name'),dataIndex: 'name',width: 100}
            ,{header: _('ms.article'),dataIndex: 'article',width: 100}
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
            text: _('ms.goods.goto_site_page')
            ,handler: this.goToGoodsSitePage
        });
        m.push({
            text: _('ms.goods.goto_manager_page')
            ,handler: this.goToGoodsManagerPage
        });
        m.push('-');
        m.push({
            text: _('ms.orderedgoods.remove')
            ,handler: this.removeGoods
        });
        this.addContextMenuItem(m);
    }
    ,addGoods: function(btn,e) {
        var w = MODx.load({
            xtype: 'minishop-window-orderedgoods'
            ,title: _('ms.orderedgoods.add')
            ,oid: this.oid
            ,newrecord: 1
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });
        w.show(e.target,function() {w.setPosition(null,100)},this);
    }
    ,updateGoods: function(btn,e,row) {
        if (typeof(row) != 'undefined') {
            var record = row.data;
        }
        else {
            var record = this.menu.record;
        }
        var w = MODx.load({
            xtype: 'minishop-window-orderedgoods'
            ,title: record.name
            ,action: 'mgr/orderedgoods/update'
            ,oid: this.oid
            ,newrecord: 0
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });
        w.fp.getForm().reset();
        w.fp.getForm().setValues(record);
        w.show(e.target,function() {w.setPosition(null,100)},this);
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
    ,goToGoodsSitePage: function() {
        var url = this.menu.record.url;
        window.open(url);
    }
    ,goToGoodsManagerPage: function() {
        var gid = this.menu.record.gid;
        window.open('/manager/index.php?a=30&id=' + gid);
    }
});

Ext.reg('minishop-grid-orderedgoods',miniShop.grid.Goods);
