<?php
class MsLog extends xPDOSimpleObject {

	function getUserName() {
		if ($res = $this->xpdo->getObject('modUser', $this->get('uid'))) {
			return $res->get('username');
		}
	}

	function getName($val = 'new') {
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
		if (empty($obj)) {return $val;}
		if ($type == 'goods') {
			if ($res = $this->xpdo->getObject($obj, $this->get('iid'))) {
				return $res->get('pagetitle');
			}
		}
		else {
			if (empty($val)) {
				return $this->xpdo->lexicon('no');
			}
			else if ($res = $this->xpdo->getObject($obj, $val)) {
				return $res->get('name');
			}
		}
	}

	function getStatusName($val = 'new') {
		return $this->getName($val);
	}

}
