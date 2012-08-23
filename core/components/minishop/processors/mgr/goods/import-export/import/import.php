<?php

sleep(5);
$offset = intval($scriptProperties['offset']);
$file = MODX_BASE_PATH . $scriptProperties['file'];
$category = $scriptProperties['category'];
$mode = $scriptProperties['mode'];


//return $modx->error->success('10');
return $modx->error->failure($file);

?>