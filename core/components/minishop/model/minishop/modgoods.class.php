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

	function addTags($data) {
		$gid = $this->get('id');
		$rid = $this->get('gid');

		$this->xpdo->removeCollection('ModTags', array('gid' => $gid, 'tag:NOT IN' => $data));
		foreach ($data as $v) {
			$v = trim($v);
			if (empty($v)) {continue;}
			if (!$tag = $this->xpdo->getObject('ModTags', array('rid' => $rid, 'gid' => $gid, 'tag' => $v))) {
				$tag = $this->xpdo->newObject('ModTags', array('rid' => $rid, 'gid' => $gid, 'tag' => $v));
				$tag->save();
			}
		}
	}

	function removeTags() {
		$gid = $this->get('id');
		$this->xpdo->removeCollection('ModTags', array('gid' => $gid));
	}

	function getTags($backend = 0) {
		$gid = $this->get('id');
		$res = $this->xpdo->getCollection('ModTags', array('gid' => $gid));
		$tags = array();
		foreach ($res as $v) {
			if ($backend) {
				$tags[] = array('tag' => $v->get('tag'));
			}
			else {
				$tags[] = $v->get('tag');
			}
			
		}
		return $tags;
	}

	

}
