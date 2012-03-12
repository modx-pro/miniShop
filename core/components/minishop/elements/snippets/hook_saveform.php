<?php
$data = $hook->getValues();

if (!empty($data['delivery'])) {$_SESSION['minishop']['delivery'] = $data['delivery'];}

$_SESSION['minishop']['address'] = $data;

return true;