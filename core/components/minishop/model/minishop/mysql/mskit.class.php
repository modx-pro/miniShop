<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mskit.class.php');
class MsKit_mysql extends MsKit {}
?>