<?php
/**
 * @package minishop
 */
class MsOrderedGood extends xPDOSimpleObject {

    public function getGoodsName() {
        if ($res = $this->getOne('Resource')) {
            return $res->get('pagetitle');
        }
        return false;
    }

    public function getGoodsParams($wid = 1) {
        if ($res = $this->xpdo->getObject('MsGood', array('gid' => $this->get('gid'), 'wid' => $wid))) {
            return $res;
        }
        return false;
    }
}
?>
