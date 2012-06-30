Ext.onReady(function() {
    MODx.load({ xtype: 'minishop-page-home'});
	
	var action = Ext.getUrlParam('act');
	var item = Ext.getUrlParam('item');
	var wid = Ext.getUrlParam('wid') || 1;
	
	if (action == 'new') {
		Ext.getCmp('minishop-tabs-main').setActiveTab('minishop-tabs-goods');
		Ext.getCmp('minishop-tabs-goods-inner').setActiveTab('minishop-tabs-goods-inner-goods');
		Ext.getCmp('minishop-grid-goods').createGoods('');
	}
	else if (action == 'edit' && typeof item != 'undefined') {
		var row = {
			data: {
				id: item
				,wid: wid
			}
		};
		Ext.getCmp('minishop-tabs-main').setActiveTab('minishop-tabs-goods');
		Ext.getCmp('minishop-tabs-goods-inner').setActiveTab('minishop-tabs-goods-inner-goods');
		Ext.getCmp('minishop-grid-goods').editGoods('','', row);
	}
	else if (action == 'tab' && typeof item != 'undefined') {
		Ext.getCmp('minishop-tabs-main').setActiveTab(Number(item));
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