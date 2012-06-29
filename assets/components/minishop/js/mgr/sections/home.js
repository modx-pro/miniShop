Ext.onReady(function() {
    MODx.load({ xtype: 'minishop-page-home'});
	
	var action = Ext.getUrlParam('act');
	var gid = Ext.getUrlParam('gid');
	var wid = Ext.getUrlParam('wid') || 1;
	if (action == 'edit' && typeof gid != 'undefined') {
		var row = {
			data: {
				id: gid
				,wid: wid
			}
		};
		Ext.getCmp('minishop-tabs-main').setActiveTab('minishop-tabs-goods');
		Ext.getCmp('minishop-tabs-goods-inner').setActiveTab('minishop-tabs-goods-inner-goods');
		Ext.getCmp('minishop-grid-goods').editGoods('','', row);
	}

});

miniShop.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'minishop-panel-home'
            ,renderTo: 'minishop-panel-home-div'
        }]
    }); 
    miniShop.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.page.Home,MODx.Component);
Ext.reg('minishop-page-home',miniShop.page.Home);

Ext.apply(Ext, {
	getUrlParam: function(param) {
		var params = Ext.urlDecode(location.search.substring(1));
		return param ? params[param] : params;
	}
});