<?php
/**
 * Create a Warehouse
 *
 * @package minishop
 * @subpackage processors
 */
class ModWarehouseCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'ModWarehouse';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modwarehouse';

    public function beforeSave() {
        $this->modx->log(modX::LOG_LEVEL_ERROR, 'before save');
        $name = $this->getProperty('name');

        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('minishop.modwarehouse_err_ns_name'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name', $this->modx->lexicon('minishop.modwarehouse_err_ae'));
        }
        return parent::beforeSave();
    }
}
return 'ModWarehouseCreateProcessor';
