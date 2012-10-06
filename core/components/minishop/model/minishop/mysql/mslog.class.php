<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mslog.class.php');
class MsLog_mysql extends MsLog {}
?>