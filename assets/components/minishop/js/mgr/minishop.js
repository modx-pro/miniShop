/**
 * @var miniShop
 * @extends Ext.Component
 * @param config
 * @constructor
 * @xtype minishop
 */
var miniShop = function(config) {
    config = config || {};
    miniShop.superclass.constructor.call(this,config);
};
Ext.extend(miniShop, Ext.Component, {
    page:{},window:{},grid:{},form:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('minishop', miniShop);

miniShop = new miniShop();
