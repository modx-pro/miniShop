<?php
/**
 * @package minishop
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/msgallery.class.php');
class MsGallery_mysql extends MsGallery {}
?>