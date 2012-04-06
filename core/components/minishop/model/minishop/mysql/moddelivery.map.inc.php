<?php
$xpdo_meta_map['ModDelivery']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'modDelivery',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'wid' => NULL,
    'name' => NULL,
    'description' => NULL,
    'price' => 0,
    'enabled' => 1,
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
    'enabled' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
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
  ),
);
