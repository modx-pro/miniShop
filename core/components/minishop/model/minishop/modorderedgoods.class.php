<?php
class ModOrderedGoods extends xPDOSimpleObject {

	function getGoodsName() { 
		if ($res = $this->xpdo->getObject('modResource', $this->get('gid'))) {
			return $res->get('pagetitle');
		}
	}

}