Ext.onReady(function() {
	MODx.load({ xtype: 'minishop-page-home'});
});

// Контейнер личного кабинета
miniShop.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'minishop-grid-orders'
			//xtype: 'minishop-panel-home'
			,renderTo: 'minishop-panel-home-div'
		}]
	}); 
	miniShop.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(miniShop.page.Home,MODx.Component);
Ext.reg('minishop-page-home',miniShop.page.Home);