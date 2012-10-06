miniShop.page.Home = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        components: [{
            xtype: 'minishop-panel-home'
            ,renderTo: 'minishop-panel-home-div'
        }]
    });
    miniShop.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(miniShop.page.Home, MODx.Component);
Ext.reg('minishop-page-home', miniShop.page.Home);


Ext.apply(Ext, {
    getUrlParam: function(param) {
        var params = Ext.urlDecode(location.search.substring(1));
        return param ? params[param] : params;
    }
});
