<?php
/**
 * @package minishop
 */
$xpdo_meta_map['MsTag']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'rid' => NULL,
    'gid' => NULL,
    'tag' => NULL,
  ),
  'fieldMeta' => 
  array (
    'rid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'gid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
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
    'tag' => 
    array (
      'alias' => 'tag',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'tag' => 
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
    'Goods' => 
    array (
      'class' => 'MsGood',
      'local' => 'gid',
      'foreign' => 'gid',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
