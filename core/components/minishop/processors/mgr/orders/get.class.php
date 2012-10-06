<?php
/**
 * Get an Order
 *
 * @package minishop
 * @subpackage processors
 */
class ModOrdersGetProcessor extends modObjectGetProcessor {
    /** @var ModOrders */
    public $object;
    public $classKey = 'ModOrders';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modorders';

    public function cleanup() {
        $objectArray = $this->object->toArray();
        $objectArray['fullname'] = $this->object->getFullName();
        $objectArray['email'] = $this->object->getEmail();
        $objectArray['sum'] += $this->object->getDeliveryPrice();

        $addresses = $this->object->getAddress();
        if ($addresses) {
            foreach ($addresses as $key => $value) {
                $objectArray['addr_' . $key] = $value;
            }
        }

        return $this->success('', $objectArray);
    }
}
return 'ModOrdersGetProcessor';
