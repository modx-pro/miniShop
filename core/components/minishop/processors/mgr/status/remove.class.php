<?php
/**
 * Remove a Status.
 *
 * @package minishop
 * @subpackage processors
 */
class ModStatusRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'ModStatus';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modstatus';
}
return 'ModStatusRemoveProcessor';
