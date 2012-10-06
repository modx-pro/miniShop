<?php
/**
 * @package minishop
 */
class MsLog extends xPDOSimpleObject {

    public function getUserName() {
        if ($res = $this->getOne('User')) {
            return $res->get('username');
        }
    }

    public function getName($val = 'new') {
        $type = $this->get('type');
        $val = $this->get($val);
        switch ($type) {
            case 'status': $obj = 'MsStatus'; break;
            case 'goods': $obj = 'modResource'; break;
            case 'delivery': $obj = 'MsDelivery'; break;
            case 'payment': $obj = 'MsPayment'; break;
            case 'warehouse': $obj = 'MsWarehouse'; break;
            default: $obj = '';
        }

        if (empty($obj)) return $val;

        if ($type == 'goods') {
            if ($res = $this->xpdo->getObject($obj, $this->get('iid'))) {
                return $res->get('pagetitle');
            }
        } else {
            if (empty($val)) {
                return $this->xpdo->lexicon('no');
            } else if ($res = $this->xpdo->getObject($obj, $val)) {
                return $res->get('name');
            }
        }
    }

    public function getStatusName($val = 'new') {
        return $this->getName($val);
    }
}
?>
