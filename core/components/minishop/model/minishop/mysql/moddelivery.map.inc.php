<?php
$xpdo_meta_map['MsDelivery']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_modDelivery',
  'extends' => 'xPDOSimpleObject',
  'fields' =>
  array (
    'wid' => NULL,
    'name' => NULL,
    'description' => NULL,
    'price' => 0,
    'add_price' => 0,
    'enabled' => 1,
    'payments' => '[]',
  ),
  'fieldMeta' =>
  array (
    'wid' =>
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'name' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'description' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'price' =>
    array (
      'dbtype' => 'float',
      'precision' => '10,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'add_price' =>
    array (
      'dbtype' => 'float',
      'precision' => '10,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'enabled' =>
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'payments' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '[]',
      'index' => 'index',
    ),
  ),
  'indexes' =>
  array (
    'wid' =>
    array (
      'alias' => 'wid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' =>
      array (
        'wid' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'payments' =>
    array (
      'alias' => 'payments',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' =>
      array (
        'payments' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
