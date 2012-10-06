<?php
/**
 * Remove a Warehouse.
 *
 * @package minishop
 * @subpackage processors
 */
class ModWarehouseRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'ModWarehouse';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modwarehouse';

    public function beforeRemove() {
        $canRemove = $this->object->get('permission');
        if (!empty($canRemove) && !$this->modx->hasPermission($canRemove)) {
            return $this->failure($this->modx->lexicon('ms.no_permission'));
        }
        return true;
    }

    public function afterRemove() {
        $id = $this->getProperty('id');
        $this->modx->removeCollection('ModDelivery', array('wid' => $id));
        //$this->modx->removeCollection('ModGoods', array('wid' => $id));
    }
}
return 'ModWarehouseRemoveProcessor';
