<?php
/**
 * @package minishop
 */
$xpdo_meta_map['MsCategory']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_categories',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'gid' => NULL,
    'cid' => NULL,
  ),
  'fieldMeta' => 
  array (
    'gid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'cid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
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
      'unique' => false,
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
    'cid' => 
    array (
      'alias' => 'cid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'cid' => 
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
    'Good' => 
    array (
      'class' => 'modResource',
      'local' => 'gid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Category' => 
    array (
      'class' => 'modResource',
      'local' => 'cid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
