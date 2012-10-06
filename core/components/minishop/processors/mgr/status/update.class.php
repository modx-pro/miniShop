<?php
/**
 * Update a Status
 *
 * @package minishop
 * @subpackage processors
 */
class ModStatusUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'ModStatus';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modstatus';
    public $defaultColor = '000000';

    public function beforeSet() {
        $this->alreadyExists();
        $email2user = $this->getProperty('email2user');
        if ($email2user) {
            $this->check('subject2user');
            $this->check('body2user');
        }
        $email2manager = $this->getProperty('email2manager');
        if ($email2manager) {
            $this->check('subject2manager');
            $this->check('body2manager');
        }
        $color = $this->getProperty('color');
        if (empty($color)) {
            $this->setProperty('color', $this->defaultColor);
        }
        return parent::beforeSet();
    }

    /**
     * Checks for required field data
     *
     * @param string $field The field name to check against
     */
    protected function check($field) {
        $value = $this->getProperty($field);
        if (empty($value)) {
            $this->addFieldError($field, $this->modx->lexicon('ms.required_field'));
        }
    }

    /**
     * Checks for an existing object with the same name
     */
    protected function alreadyExists() {
        $c = $this->modx->newQuery($this->classKey);
        $c->where(array(
            'id:!=' => $this->getProperty('id'),
            'name' => $this->getProperty('name'),
        ));

        if ($this->modx->getCount($this->classKey, $c) >= 1) {
            $this->addFieldError('name', $this->modx->lexicon('ms.status.err_ae'));
        }
    }
}
return 'ModStatusUpdateProcessor';
