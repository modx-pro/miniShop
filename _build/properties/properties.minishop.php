<?
/**
 * Properties for the miniShop snippet.
 *
 * @package minishop
 * @subpackage build
 */
$properties = array();

$properties[0] = array();
$properties[1] = array();
$properties[2] = array();
$properties[3] = array();
$properties[4] = array();

$properties[5] = array(
	array(
		'name' => 'tplCartOuter',
		'value' => 'tpl.msCart.outer',
		'type' => 'textfield',
		'desc' => 'ms.tplCartOuter',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplCartRow',
		'value' => 'tpl.msCart.row',
		'type' => 'textfield',
		'desc' => 'ms.tplCartRow',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplDeliveryRow',
		'value' => 'tpl.msDelivery.row',
		'type' => 'textfield',
		'desc' => 'ms.tplDeliveryRow',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplPaymentRow',
		'value' => 'tpl.msDelivery.row',
		'type' => 'textfield',
		'desc' => 'ms.tplPaymentRow',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplAddrForm',
		'value' => 'tpl.msAddrForm',
		'type' => 'textfield',
		'desc' => 'ms.tplAddrForm',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplOrderEmailUser',
		'value' => 'tpl.msOrderEmail.user',
		'type' => 'textfield',
		'desc' => 'ms.tplOrderEmailUser',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplOrderEmailManager',
		'value' => 'tpl.msOrderEmail.manager',
		'type' => 'textfield',
		'desc' => 'ms.tplOrderEmailManager',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplOrderEmailRow',
		'value' => 'tpl.msOrderEmail.row',
		'type' => 'textfield',
		'desc' => 'ms.tplOrderEmailRow',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplSubmitOrderSuccess',
		'value' => 'tpl.msSubmitOrder.success',
		'type' => 'textfield',
		'desc' => 'ms.tplSubmitOrderSuccess',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplMyOrdersList',
		'value' => 'tpl.msMyOrdersList',
		'type' => 'textfield',
		'desc' => 'ms.tplMyOrdersList',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tplPaymentForm',
		'value' => 'tpl.msPayment.form',
		'type' => 'textfield',
		'desc' => 'ms.tplPaymentForm',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'debug',
		'value' => false,
		'type' => 'combo-boolean',
		'desc' => 'ms.debug',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'userGroups',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'ms.userGroups',
		'lexicon' => 'minishop:properties',
		'options' => '',
	)
);

$properties[6] = array();
$properties[7] = array();
$properties[8] = array();

$properties[9] = array(
	array(
		'name' => 'id',
		'value' => 0,
		'type' => 'numberfield',
		'desc' => 'ms.id',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'tpl',
		'value' => 'tpl.msGallery.row',
		'type' => 'textfield',
		'desc' => 'ms.tpl',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'limit',
		'value' => 10,
		'type' => 'numberfield',
		'desc' => 'ms.limit',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'offset',
		'value' => 0,
		'type' => 'numberfield',
		'desc' => 'ms.offset',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'outputSeparator',
		'value' => "\n",
		'type' => 'textfield',
		'desc' => 'ms.outputSeparator',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'totalVar',
		'value' => 'total',
		'type' => 'textfield',
		'desc' => 'ms.totalVar',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'sortby',
		'value' => 'id',
		'type' => 'textfield',
		'desc' => 'ms.sortby',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
	array(
		'name' => 'sortdir',
		'value' => 'ASC',
		'type' => 'list',
		'desc' => 'ms.sortdir',
		'lexicon' => 'minishop:properties',
        'options' => array(
            array('text' => 'ASC','value' => 'ASC'),
            array('text' => 'DESC','value' => 'DESC'),
        ),
	),
	array(
		'name' => 'onlyImg',
		'value' => true,
		'type' => 'combo-boolean',
		'desc' => 'ms.onlyImg',
		'lexicon' => 'minishop:properties',
		'options' => '',
	),
);

return $properties;
