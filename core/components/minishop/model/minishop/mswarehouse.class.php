<?php
/**
 * @package minishop
 */
class MsWarehouse extends xPDOSimpleObject {

    public function getDeliveries() {
        $tmp = $this->getMany('Deliveries');
        $arr = array();
        /** @var MsDelivery $v */
        foreach ($tmp as $v) {
            $arr[] = $v->get('id');
        }
        return $arr;
    }
}
?>
