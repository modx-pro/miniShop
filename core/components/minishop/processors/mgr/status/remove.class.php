<?php
/**
 * Remove a Status.
 *
 * @package minishop
 * @subpackage processors
 */
class MsStatusRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'MsStatus';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modstatus';
}
return 'MsStatusRemoveProcessor';
