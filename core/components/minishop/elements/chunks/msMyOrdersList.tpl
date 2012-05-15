<script src="/manager/assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="/manager/assets/ext3/ext-all.js" type="text/javascript"></script>
<script src="/manager/assets/modext/core/modx.js" type="text/javascript"></script>
<script src="/connectors/lang.js.php?ctx=web&topic=minishop:default" type="text/javascript"></script>

<script type="text/javascript" src="/manager/assets/modext/util/utilities.js"></script>
<script type="text/javascript" src="/manager/assets/modext/core/modx.component.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.panel.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.tabs.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.window.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.tree.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.combo.js"></script>
<script type="text/javascript" src="/manager/assets/modext/widgets/core/modx.grid.js"></script>

<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.4.0/resources/css/ext-all-notheme.css" />
<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.4.0/resources/css/xtheme-gray.css" />

<script type="text/javascript">
	Ext.onReady(function() {
		miniShop.config = [[+config]];
		miniShop.config.connector_url = "[[~[[*id]]]]";
		miniShop.action = 0;
	});
</script>

<script type="text/javascript" src="/assets/components/minishop/js/web/web.js"></script>
<!--<script type="text/javascript" src="/assets/modext/util/datetime.js"></script>-->
<script type="text/javascript" src="/assets/components/minishop/js/web/widgets/orders.grid.js"></script>
<script type="text/javascript" src="/assets/components/minishop/js/web/widgets/home.panel.js"></script>
<script type="text/javascript" src="/assets/components/minishop/js/web/sections/home.js"></script>

<style type="text/css">
	body {overflow: auto !important;}
</style>


<div id="modx-content">
	<div id="minishop-panel-home-div"></div>
</div>