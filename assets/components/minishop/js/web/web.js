// Осноыной объект магазина
var miniShop = function(config) {
	config = config || {};
	miniShop.superclass.constructor.call(this,config);
};
Ext.extend(miniShop,Ext.Component,{
	page:{},window:{},grid:{},form:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('minishop',miniShop);

miniShop = new miniShop();

// Старт при загрузке
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-layout'});
});


// Разметка страницы по умолчанию - нужна для Revo 2.2
MODx.Layout = function(config){
	config = config || {};
	Ext.applyIf(config,{
		applyTo: 'modx-content'	
		,id: 'modx-content'
	});
	MODx.Layout.superclass.constructor.call(this,config);
}
Ext.extend(MODx.Layout,Ext.Viewport);
Ext.reg('modx-layout',MODx.Layout);
