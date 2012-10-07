<?php
/**
 * @package minishop
 */
class MsPayment extends xPDOSimpleObject {

    public function getSnippetName() {
        if ($res = $this->getOne('Snippet')) {
            return $res->get('name');
        }
        return false;
    }
}
?>
