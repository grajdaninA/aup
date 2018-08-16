<?php
include 'common.php';

$view = new View();
$page = $view -> getTemplate('top');
$view -> display($page);
unset($view);
?>
