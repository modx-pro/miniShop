<?php
class ModLog extends xPDOSimpleObject {

	function getUserName() {
		if ($res = $this->xpdo->getObject('modUser', $this->get('uid'))) {
			return $res->get('username');
		}
	}

	function getName($val = 'new') {
		$type = $this->get('type');
		$val = $this->get($val);
		switch ($type) {
			case 'status': $obj = 'ModStatus'; break;
			case 'goods': $obj = 'modResource'; break;
			case 'delivery': $obj = 'ModDelivery'; break;
			case 'payment': $obj = 'ModPayment'; break;
			case 'warehouse': $obj = 'ModWarehouse'; break;
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
