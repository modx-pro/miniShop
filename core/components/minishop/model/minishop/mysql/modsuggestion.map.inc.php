<?php
$xpdo_meta_map['ModSuggestion']= array (
  'package' => 'minishop',
  'version' => '1.1',
  'table' => 'modSuggestion',
  'fields' => 
  array (
    'gid' => NULL,
    'suggestion' => NULL,
    'bought' => NULL,
  ),
  'fieldMeta' => 
  array (
    'gid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'suggestion' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'bought' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
);
