<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mstag.class.php');
class MsTag_mysql extends MsTag {}
?>