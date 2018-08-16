<?php
 require "common.php";
$view = new View();
$page = $view -> getTemplate('index_frame');
$view -> display($page);
unset($view);
?>
 