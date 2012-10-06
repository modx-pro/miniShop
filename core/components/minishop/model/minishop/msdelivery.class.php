<?php
/**
 * @package minishop
 */
class MsDelivery extends xPDOSimpleObject {

    public function getPayments() {
        $tmp = $this->get('payments');
        if ($res = json_decode($tmp, true)) {
            return $res;
        }
        return array();
    }
}
?>
