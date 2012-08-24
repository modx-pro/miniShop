<?php
$xpdo_meta_map['ModKits']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_modKits',
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
        'gid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
