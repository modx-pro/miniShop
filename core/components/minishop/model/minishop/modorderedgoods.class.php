<?php
class ModOrderedGoods extends xPDOSimpleObject {

	function getGoodsName() { 
		if ($res = $this->xpdo->getObject('modResource', $this->get('gid'))) {
			return $res->get('pagetitle');
		}
		else {return false;}
	}
	
	function getGoodsParams($wid = 1) {
		if ($res = $this->xpdo->getObject('ModGoods', array('gid' => $this->get('gid'), 'wid' => $wid))) {
			return $res;
		}
		else {return false;}
	}

}