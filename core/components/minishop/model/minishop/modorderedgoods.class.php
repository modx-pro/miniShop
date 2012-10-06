<?php
class MsOrderedGood extends xPDOSimpleObject {

	function getGoodsName() {
		if ($res = $this->xpdo->getObject('modResource', $this->get('gid'))) {
			return $res->get('pagetitle');
		}
		else {return false;}
	}

	function getGoodsParams($wid = 1) {
		if ($res = $this->xpdo->getObject('MsGood', array('gid' => $this->get('gid'), 'wid' => $wid))) {
			return $res;
		}
		else {return false;}
	}

}
