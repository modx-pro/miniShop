<?php
/**
 * @package minishop
 */
$xpdo_meta_map['MsGood']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_goods',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'wid' => 0,
    'gid' => 0,
    'article' => '0',
    'price' => 0,
    'weight' => 0,
    'img' => NULL,
    'remains' => 0,
    'reserved' => 0,
    'add1' => NULL,
    'add2' => NULL,
    'add3' => NULL,
  ),
  'fieldMeta' => 
  array (
    'wid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'gid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'article' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'price' => 
    array (
      'dbtype' => 'float',
      'precision' => '10,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'weight' => 
    array (
      'dbtype' => 'float',
      'precision' => '10,3',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'img' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'remains' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'reserved' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'add1' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'add2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'add3' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'wid' => 
    array (
      'alias' => 'wid',
      'primary' => false,
      'unique' => true,
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
    'gid' => 
    array (
      'alias' => 'gid',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'gid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Warehouse' => 
    array (
      'class' => 'MsWarehouse',
      'local' => 'wid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Good' => 
    array (
      'class' => 'MsGood',
      'local' => 'gid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Tags' => 
    array (
      'class' => 'MsTag',
      'local' => 'gid',
      'foreign' => 'gid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
    'Kits' => 
    array (
      'class' => 'MsKit',
      'local' => 'gid',
      'foreign' => 'gid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
    'Galleries' => 
    array (
      'class' => 'MsGallery',
      'local' => 'gid',
      'foreign' => 'gid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
  ),
);
