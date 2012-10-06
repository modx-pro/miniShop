<?php
/**
 * @package minishop
 */
class MsOrder extends xPDOSimpleObject {
    /**
     * Returns the username of the modUser who did the order
     *
     * @return string The username
     */
    public function getUserName() {
        if ($user = $this->getOne('User')) {
            return $user->get('username');
        }
    }

    public function getFullName() {
        if ($profile = $this->getOne('User')->getOne('Profile')) {
            return $profile->get('fullname');
        }
    }

    public function getEmail() {
        if ($res = $this->xpdo->getObject('modUserProfile', array('internalKey' => $this->get('uid')))) {
            return $res->get('email');
        }
    }

    public function getStatusName() {
        if ($status = $this->getOne('Status')) {
            return $status->get('name');
        }
    }

    public function getWarehouseName() {
        if ($warehouse = $this->getOne('Warehouse')) {
            return $warehouse->get('name');
        }
    }

    public function getAddress() {
        if ($address = $this->getOne('Address')) {
            return $address->toArray();
        }
    }

    public function updateSum() {
        if ($res = $this->getMany('Goods')) {
            $sum = 0;
            /** @var MsOrderedGood $v */
            foreach ($res as $v) {
                $sum += $v->get('sum');
            }
            $this->set('sum', $sum);
            $this->save();
        }
    }

    function updateWeight() {
        if ($res = $this->getMany('Goods')) {
            $weight = 0;
            /** @var MsOrderedGood $v */
            foreach ($res as $v) {
                $weight += $v->get('weight');
            }
            $this->set('weight', $weight);
            $this->save();
        }
    }

    function getDeliveryName() {
        if ($res = $this->getOne('Delivery')) {
            return $res->get('name');
        }
    }

    public function getPaymentName() {
        if ($res = $this->getOne('MsPayment')) {
            return $res->get('name');
        }
    }

    public function getDeliveryPrice() {
        $weight = $this->get('weight');

        if ($res = $this->getOne('MsDelivery')) {
            $price = $res->get('price');
            $add_price = $res->get('add_price');

            $sum = round($weight * $price, 2);

            return $sum + $add_price;
        }
        return 0;
    }

    public function unReserve() {
        $oid = $this->get('id');
        $wid = $this->get('wid');

        $res = $this->getMany('Goods');
        /** @var MsOrderedGood $v */
        foreach ($res as $v) {
            $gid = $v->get('gid');
            $num = $v->get('num');

            if ($res2 = $this->xpdo->getObject('MsGood', array('gid' => $gid, 'wid' => $wid))) {
                $reserved = $res2->get('reserved') - $num;
                $res2->set('reserved', $reserved);
                $res2->save();
            }
        }
    }

    public function releaseReserved() {
        $oid = $this->get('id');
        $wid = $this->get('wid');

        $res = $this->getMany('Goods');
        /** @var MsOrderedGood $v */
        foreach ($res as $v) {
            $gid = $v->get('gid');
            $num = $v->get('num');

            if ($res2 = $this->xpdo->getObject('MsGood', array('gid' => $gid, 'wid' => $wid))) {
                $res2->release($num);
            }
        }
    }

    public function getOrderedGoods() {
        $arr = array();

        $res = $this->getMany('Goods');
        foreach ($res as $v) {
            $arr[] = $v;
        }

        return $arr;
    }
}
?>
