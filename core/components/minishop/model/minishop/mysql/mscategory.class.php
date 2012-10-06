<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mscategory.class.php');
class MsCategory_mysql extends MsCategory {}
?>