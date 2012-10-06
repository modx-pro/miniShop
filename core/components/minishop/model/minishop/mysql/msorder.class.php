<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msorder.class.php');
class MsOrder_mysql extends MsOrder {}
?>