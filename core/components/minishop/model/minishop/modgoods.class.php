<?php
class ModGoods extends xPDOSimpleObject {

	function reserve($num = 0, $dir = 1) {
		if (empty($num)) {return false;}
		
		$remains = $this->get('remains');
		$reserved = $this->get('reserved');
		if ($dir == 1) {
			$remains_new = $remains - $num;
			$reserved_new = $reserved + $num;
		}
		else {
			$remains_new = $remains + $num;
			$reserved_new = $reserved - $num;
		}
		
		if ($remains_new <= 0) {
			$this->xpdo->log(modX::LOG_LEVEL_ERROR,'The negative balance of goods #'.$v['id'].' in warehouse #'.$_SESSION['minishop']['warehouse']);
		}
		
		$this->set('remains', $remains_new);
		$this->set('reserved', $reserved_new);
		
		if ($this->save()) {
			if (!isset($this->xpdo->miniShop) || !is_object($this->xpdo->miniShop)) {
				$this->xpdo->miniShop = new miniShop($this->xpdo);
			}
			$this->xpdo->miniShop->Log('goods', $this->get('gid'), 'remains', $remains, $remains_new);
			return true;
		}
	}
	
	
	function release($num = 0) {
		return $this->reserve($num, 2);
	}

}