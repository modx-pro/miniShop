<?php
$xpdo_meta_map['ModTags']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'modTags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'gid' => NULL,
    'tag' => NULL,
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
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
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
        'tag' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
