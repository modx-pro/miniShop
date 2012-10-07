<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msdelivery.class.php');
class MsDelivery_mysql extends MsDelivery {}
?>