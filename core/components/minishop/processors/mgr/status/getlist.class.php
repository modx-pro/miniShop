<?php
/**
 * Get a list of Statuses
 *
 * @package minishop
 * @subpackage processors
 */
class MsStatusGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'MsStatus';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modstatus';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }
}
return 'MsStatusGetListProcessor';
