<?php
class ModOrders extends xPDOSimpleObject {

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
		if ($res = $this->xpdo->getObject('ModStatus', $this->get('status'))) {
			return $res->get('name');
		}
	}

	function getWarehouseName() {
		if ($res = $this->xpdo->getObject('ModWarehouse', $this->get('wid'))) {
			return $res->get('name');
		}     
	}

	function getAddress() {
		if ($res = $this->xpdo->getObject('ModAddress', $this->get('address'))) {
			return $res->toArray();
		}
	}

	function updateSum() {
		if ($res = $this->xpdo->getCollection('ModOrderedGoods', array('oid' => $this->get('id')))) {
			$sum = 0;
			foreach ($res as $v) {
				$sum += $v->get('sum');
			}
			$this->set('sum', $sum);
			$this->save();
		}
	}

	function updateWeight() {
		if ($res = $this->xpdo->getCollection('ModOrderedGoods', array('oid' => $this->get('id')))) {
			$weight = 0;
			foreach ($res as $v) {
				$weight += $v->get('weight');
			}
			$this->set('weight', $weight);
			$this->save();
		}
	}
	
	function getDeliveryName() {
		if ($res = $this->xpdo->getObject('ModDelivery', $this->get('delivery'))) {
			return $res->get('name');
		}
	}

	function getPaymentName() {
		if ($res = $this->xpdo->getObject('ModPayment', $this->get('payment'))) {
			return $res->get('name');
		}
	}

	function getDeliveryPrice() {
		$count = $this->get('count'); //Количество товаров

		if ($res = $this->xpdo->getObject('ModDelivery', $this->get('delivery'))) {
			$price = $res->get('price');
			$add_price = $res->get('add_price');

			$sum = round($count * $add_price, 2); // Умножаем количество на цену доставки 1 товара, округляем

			return $sum + $price; // К конечной стоимости добавляем стоимость доставки
		}
		else {return 0;}
	}

	function unReserve() {
		$oid = $this->get('id');
		$wid = $this->get('wid');
		
		$res = $this->xpdo->getIterator('ModOrderedGoods', array('oid' => $oid));
		foreach ($res as $v) {
			$gid = $v->get('gid');
			$num = $v->get('num');
			
			if ($res2 = $this->xpdo->getObject('ModGoods', array('gid' => $gid, 'wid' => $wid))) {
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
		
		$res = $this->xpdo->getIterator('ModOrderedGoods', array('oid' => $oid));
		foreach ($res as $v) {
			$gid = $v->get('gid');
			$num = $v->get('num');
			
			if ($res2 = $this->xpdo->getObject('ModGoods', array('gid' => $gid, 'wid' => $wid))) {
				$res2->release($num);
			}
		}
	}

}
