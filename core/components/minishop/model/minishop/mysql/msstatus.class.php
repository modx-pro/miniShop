<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msstatus.class.php');
class MsStatus_mysql extends MsStatus {}
?>