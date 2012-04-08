<?php
class ModPayment extends xPDOSimpleObject {

	function getSnippetName() {
		if ($res = $this->xpdo->getObject('modSnippet', $this->get('snippet'))) {
			return $res->get('name');
		}
		else {
			return false;
		}
	}
}