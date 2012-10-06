<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/mspayment.class.php');
class MsPayment_mysql extends MsPayment {}
?>