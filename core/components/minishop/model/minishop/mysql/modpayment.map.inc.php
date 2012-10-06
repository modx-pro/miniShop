<?php
$xpdo_meta_map['MsPayment']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'ms_modPayment',
  'extends' => 'xPDOSimpleObject',
  'fields' =>
  array (
    'name' => NULL,
    'description' => NULL,
    'snippet' => 0,
  ),
  'fieldMeta' =>
  array (
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
      'null' => true,
    ),
    'snippet' =>
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
);
