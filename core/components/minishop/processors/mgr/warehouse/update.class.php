<?php
/**
 * Update a Warehouse
 *
 * @package minishop
 * @subpackage processors
 */
class MsWarehouseUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'MsWarehouse';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modwarehouse';

    public function beforeSet() {
        // @todo : make use of $this->permission
        $canSave = $this->object->get('permission');
        if (!empty($canSave) && !$this->modx->hasPermission($canSave)) {
            return $this->failure($this->modx->lexicon('ms.no_permission'));
        }
        return true;
    }
}
return 'MsWarehouseUpdateProcessor';
