<?php
/**
 * Remove a Warehouse.
 *
 * @package minishop
 * @subpackage processors
 */
class MsWarehouseRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'MsWarehouse';
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
        $this->modx->removeCollection('MsDelivery', array('wid' => $id));
        //$this->modx->removeCollection('MsGood', array('wid' => $id));
    }
}
return 'MsWarehouseRemoveProcessor';
