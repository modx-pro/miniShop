<?php
class ModDelivery extends xPDOSimpleObject {
	
	function getPayments() {
		$tmp = $this->get('payments');
		
		if ($res = json_decode($tmp, true)) {
			return $res;
		}
		else {
			return array();
		}
	}
	
}