<?php
/**
 * @package minishop
 */
$xpdo_meta_map['MsWarehouse']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_warehouses',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'currency' => NULL,
    'address' => NULL,
    'phone' => NULL,
    'email' => NULL,
    'description' => NULL,
    'permission' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'currency' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
    ),
    'address' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'permission' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Goods' => 
    array (
      'class' => 'MsGood',
      'local' => 'id',
      'foreign' => 'wid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Orders' => 
    array (
      'class' => 'MsOrder',
      'local' => 'id',
      'foreign' => 'wid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Galleries' => 
    array (
      'class' => 'MsGallery',
      'local' => 'id',
      'foreign' => 'wid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Deliveries' => 
    array (
      'class' => 'MsDelivery',
      'local' => 'id',
      'foreign' => 'wid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
