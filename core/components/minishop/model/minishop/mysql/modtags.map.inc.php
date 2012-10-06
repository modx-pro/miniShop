<?php
$xpdo_meta_map['MsTag']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_modTags',
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
