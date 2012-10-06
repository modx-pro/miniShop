<?php
/**
 * Create a Payment
 *
 * @package minishop
 * @subpackage processors
 */
class MsPaymentCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'MsPayment';
    public $languageTopics = array('minishop:default');
    public $objectType = 'minishop.modpayment';
}
return 'MsPaymentCreateProcessor';
