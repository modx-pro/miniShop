<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msaddress.class.php');
class MsAddress_mysql extends MsAddress {}
?>