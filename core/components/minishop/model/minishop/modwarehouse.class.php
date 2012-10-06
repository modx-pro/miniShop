<?php
class MsWarehouse extends xPDOSimpleObject {

	function getDeliveries() {
		$id = $this->get('id');

		$tmp = $this->xpdo->getCollection('MsDelivery', array('wid' => $id));
		$arr = array();
		foreach ($tmp as $v) {
			$arr[] = $v->get('id');
		}
		return $arr;
	}

}
