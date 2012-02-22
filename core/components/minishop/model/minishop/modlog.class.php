<?php
class ModLog extends xPDOSimpleObject {

	function getUserName() {
		if ($res = $this->xpdo->getObject('modUser', $this->get('uid'))) {
			return $res->get('username');
		}
	}

	function getStatusName($val = 'new') {
		if ($res = $this->xpdo->getObject('ModStatus', $this->get($val))) {
			return $res->get('name');
		}
	}

}