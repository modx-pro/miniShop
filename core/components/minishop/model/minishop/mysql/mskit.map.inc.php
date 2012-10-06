<?php
/**
 * @package minishop
 */
$xpdo_meta_map['MsKit']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_kits',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'rid' => NULL,
    'gid' => NULL,
  ),
  'fieldMeta' => 
  array (
    'rid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'gid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'rid' => 
    array (
      'alias' => 'rid',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'rid' => 
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
    'Resource' => 
    array (
      'class' => 'modResource',
      'local' => 'rid',
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
  ),
);
