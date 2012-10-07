<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mswarehouse.class.php');
class MsWarehouse_mysql extends MsWarehouse {}
?>