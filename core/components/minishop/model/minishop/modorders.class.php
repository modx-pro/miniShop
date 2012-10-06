<?php
class MsOrder extends xPDOSimpleObject {

	function getUserName() {
		if ($res = $this->xpdo->getObject('modUser', $this->get('uid'))) {
			return $res->get('username');
		}
	}

	function getFullName() {
		if ($res = $this->xpdo->getObject('modUserProfile', array('internalKey' => $this->get('uid')))) {
			return $res->get('fullname');
		}
	}

	function getEmail() {
		if ($res = $this->xpdo->getObject('modUserProfile', array('internalKey' => $this->get('uid')))) {
			return $res->get('email');
		}
	}

	function getStatusName() {
		if ($res = $this->xpdo->getObject('MsStatus', $this->get('status'))) {
			return $res->get('name');
		}
	}

	function getWarehouseName() {
		if ($res = $this->xpdo->getObject('MsWarehouse', $this->get('wid'))) {
			return $res->get('name');
		}
	}

	function getAddress() {
		if ($res = $this->xpdo->getObject('MsAddress', $this->get('address'))) {
			return $res->toArray();
		}
	}

	function updateSum() {
		if ($res = $this->xpdo->getCollection('MsOrderedGood', array('oid' => $this->get('id')))) {
			$sum = 0;
			foreach ($res as $v) {
				$sum += $v->get('sum');
			}
			$this->set('sum', $sum);
			$this->save();
		}
	}

	function updateWeight() {
		if ($res = $this->xpdo->getCollection('MsOrderedGood', array('oid' => $this->get('id')))) {
			$weight = 0;
			foreach ($res as $v) {
				$weight += $v->get('weight');
			}
			$this->set('weight', $weight);
			$this->save();
		}
	}

	function getDeliveryName() {
		if ($res = $this->xpdo->getObject('MsDelivery', $this->get('delivery'))) {
			return $res->get('name');
		}
	}

	function getPaymentName() {
		if ($res = $this->xpdo->getObject('MsPayment', $this->get('payment'))) {
			return $res->get('name');
		}
	}

	function getDeliveryPrice() {
		$weight = $this->get('weight');

		if ($res = $this->xpdo->getObject('MsDelivery', $this->get('delivery'))) {
			$price = $res->get('price');
			$add_price = $res->get('add_price');

			$sum = round($weight * $price, 2);

			return $sum + $add_price;
		}
		else {return 0;}
	}

	function unReserve() {
		$oid = $this->get('id');
		$wid = $this->get('wid');

		$res = $this->xpdo->getIterator('MsOrderedGood', array('oid' => $oid));
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

	function releaseReserved() {
		$oid = $this->get('id');
		$wid = $this->get('wid');
		$miniShop = new miniShop($this->xpdo);

		$res = $this->xpdo->getIterator('MsOrderedGood', array('oid' => $oid));
		foreach ($res as $v) {
			$gid = $v->get('gid');
			$num = $v->get('num');

			if ($res2 = $this->xpdo->getObject('MsGood', array('gid' => $gid, 'wid' => $wid))) {
				$res2->release($num);
			}
		}
	}

	function getOrderedGoods() {
		$arr = array();

		$res = $this->xpdo->getIterator('MsOrderedGood', array('oid' => $this->get('id')));
		foreach ($res as $v) {
			$arr[] = $v;
		}

		return $arr;
	}

}
