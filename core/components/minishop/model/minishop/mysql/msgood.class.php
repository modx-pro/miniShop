<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msgood.class.php');
class MsGood_mysql extends MsGood {}
?>