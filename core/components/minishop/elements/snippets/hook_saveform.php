<?php
/**
 * @var modX $modx
 * @var fiHooks $hook
 */
$data = $hook->getValues();

if (!empty($data['delivery'])) {$_SESSION['minishop']['delivery'] = $data['delivery'];}
if (!empty($data['payment'])) {$_SESSION['minishop']['payment'] = $data['payment'];}

$_SESSION['minishop']['address'] = $data;

return true;
?>
