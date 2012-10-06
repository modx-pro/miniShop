<?php
class MsGood extends xPDOSimpleObject {

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
			//if (!isset($this->xpdo->miniShop) || !is_object($this->xpdo->miniShop)) {
			//	$this->xpdo->miniShop = new miniShop($this->xpdo);
			//}
			//$this->xpdo->miniShop->Log('goods', $this->get('gid'), 'remains', $remains, $remains_new);
			return true;
		}
	}


	function release($num = 0) {
		return $this->reserve($num, 2);
	}

	function addTags($data) {
		$gid = $this->get('id');
		$rid = $this->get('gid');

		$this->xpdo->removeCollection('MsTag', array('gid' => $gid, 'tag:NOT IN' => $data));
		foreach ($data as $v) {
			$v = trim($v);
			if (empty($v)) {continue;}
			if (!$tag = $this->xpdo->getObject('MsTag', array('rid' => $rid, 'gid' => $gid, 'tag' => $v))) {
				$tag = $this->xpdo->newObject('MsTag', array('rid' => $rid, 'gid' => $gid, 'tag' => $v));
				$tag->save();
			}
		}
	}

	function removeTags() {
		$gid = $this->get('id');
		$this->xpdo->removeCollection('MsTag', array('gid' => $gid));
	}

	function getTags($backend = 0) {
		$gid = $this->get('id');
		$res = $this->xpdo->getCollection('MsTag', array('gid' => $gid));
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

	function getKits($only_ids = 0, $sort = '', $dir = 'ASC') {
		$q = $this->xpdo->newQuery('MsKit', array('gid' => $this->get('gid')));
		$q->select('rid');
		if ($q->prepare() && $q->stmt->execute()) {
			$ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		}

		if (empty($ids)) {return array();}

		$q = $this->xpdo->newQuery('MsKit', array('rid:IN' => $ids));
		if (!empty($sort)) {
			$q->leftJoin('modResource','modResource', array('modResource.id = MsKit.gid'));
			$q->sortby('modResource.'.$sort, $dir);
		}

		//$q->prepare();echo $q->toSql();die;
		$res = $this->xpdo->getCollection('MsKit', $q);
		$arr = array();
		foreach ($res as $v) {
			$key = $v->get('rid');
			$gid = $v->get('gid');
			if (!array_key_exists($key, $arr)) {
				if (!$only_ids && $tmp = $this->xpdo->getObject('modResource', $key)) {
					$arr[$key] = $tmp->toArray();
				}
				else {
					$arr[$key] = array('id' => $key);
				}
				$arr[$key]['resources'] = array();
			}

			if (!$only_ids && $tmp = $this->xpdo->getObject('modResource', $gid)) {
				$arr[$key]['resources'][] = $tmp->toArray();
			}
			else {
				$arr[$key]['resources'][] = $gid;
			}
		}
		return $arr;
	}

	function getGallery($sort = 'fileorder', $dir = 'ASC', $limit = 0) {
		$gid = $this->get('gid');

		$q = $this->xpdo->newQuery('MsGallery', array('gid' => $gid, 'wid' => $_SESSION['minishop']['warehouse']));
		$q->sortby($sort,$dir);
		if (!empty($limit)) {
			$q->limit($limit);
		}
		$files = $this->xpdo->getCollection('MsGallery', $q);

		$arr = array();
		foreach ($files as $v) {
			$arr[] = $v;
		}
		return $arr;
	}

}
