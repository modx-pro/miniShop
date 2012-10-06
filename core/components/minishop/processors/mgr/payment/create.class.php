<?php
/**
 * Create a Payment
 *
 * @package minishop
 * @subpackage processors
 */
class ModPaymentCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'ModPayment';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modpayment';
}
return 'ModPaymentCreateProcessor';
