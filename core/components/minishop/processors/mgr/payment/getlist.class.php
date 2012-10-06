<?php
/**
 * Get a list of Payments
 *
 * @package minishop
 * @subpackage processors
 */
class MsPaymentGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'MsPayment';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modpayment';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%',
                'OR:description:LIKE' => '%'.$query.'%',
                //'OR:snippet:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        /** @var modSnippet $snippet */
        $snippet = $this->modx->getObject('modSnippet', $objectArray['snippet']);
        // @todo : make use of the aggregate
        //$snippet = $object->getOne('Snippet');
        if ($snippet) {
            $objectArray['snippet'] = $snippet->get('name');
        } else {
            $objectArray['snippet'] = '';
        }
        return $objectArray;
    }
}
return 'MsPaymentGetListProcessor';
