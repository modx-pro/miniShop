<?php
/**
 * Loads the header for mgr pages.
 *
 * @package minishop
 * @subpackage controllers
 */
//$modx->regClientCSS($miniShop->config['cssUrl'].'mgr.css');
$modx->regClientStartupScript($miniShop->config['jsUrl'].'mgr/minishop.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    miniShop.config = '.$modx->toJSON($miniShop->config).';
    miniShop.config.connector_url = "'.$miniShop->config['connectorUrl'].'";
    miniShop.config.connectors_url = "'.$miniShop->config['connectorsUrl'].'";
    miniShop.action = "'.(!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0).'";
    miniShop.config.warehouse = "'.$_SESSION['minishop']['warehouse'].'";
    miniShop.config.category = "'.$_SESSION['minishop']['category'].'";
    miniShop.config.status = "'.$_SESSION['minishop']['status'].'";
    miniShop.config.statuses = '.$miniShop->config['statuses'].';
});
</script>');

// RichText editors
if ($modx->getOption('use_editor') == 1) {
	// TinyMCE
	if (strtolower($modx->getOption('which_editor')) == 'tinymce') {
		$modx->regClientStartupScript($modx->getOption('assets_url').'components/tinymce/jscripts/tiny_mce/tiny_mce.js');		
		for ($i=1; $i<6; $i++)
			${'cb'.$i} = $modx->getOption('minishop.tiny.custom_buttons'.$i);
		
		$height     = $modx->getOption('minishop.tiny.height',null,200);
		$width      = $modx->getOption('minishop.tiny.width',null,400);
		$plugins    = $modx->getOption('minishop.tiny.custom_plugins');
		$theme      = $modx->getOption('minishop.tiny.editor_theme');
		$bfs        = $modx->getOption('minishop.tiny.theme_advanced_blockformats');
		$css        = $modx->getOption('minishop.tiny.css_selectors');
		
		$tinyProperties = array(
			'height' => $height,
			'width' => $width,
			'tiny.custom_buttons1' => (!empty($cb1)) ? $cb1 : $modx->getOption('tiny.custom_buttons1'),
			'tiny.custom_buttons2' => (!empty($cb2)) ? $cb2 : $modx->getOption('tiny.custom_buttons2'),
			'tiny.custom_buttons3' => (!empty($cb3)) ? $cb3 : $modx->getOption('tiny.custom_buttons3'),
			'tiny.custom_buttons4' => (!empty($cb4)) ? $cb4 : $modx->getOption('tiny.custom_buttons4'),
			'tiny.custom_buttons5' => (!empty($cb5)) ? $cb5 : $modx->getOption('tiny.custom_buttons5'),
			'tiny.custom_plugins' => (!empty($plugins)) ? $plugins : $modx->getOption('tiny.custom_plugins'),
			'tiny.editor_theme' => (!empty($theme)) ? $theme : $modx->getOption('tiny.editor_theme'),
			'tiny.theme_advanced_blockformats' => (!empty($bfs)) ? $bfs : $modx->getOption('tiny.theme_advanced_blockformats'),
			'tiny.css_selectors' => (!empty($css)) ? $css : $modx->getOption('tiny.css_selectors')
		);

		$tinyCorePath = $modx->getOption('core_path').'components/tinymce/';
		require_once $tinyCorePath.'tinymce.class.php';
		$tiny = new TinyMCE($modx,$tinyProperties);
		$tiny->setProperties($tinyProperties);
		$html = $tiny->initialize();
		$modx->regClientHTMLBlock($html);
	}
}

return '';