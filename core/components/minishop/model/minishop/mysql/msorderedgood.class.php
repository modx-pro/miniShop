<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msorderedgood.class.php');
class MsOrderedGood_mysql extends MsOrderedGood {}
?>