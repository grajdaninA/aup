<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require "common.php";
$id_obj = $_GET['id_obj'];
$mode = $_GET['mode'];
if (empty($id_obj)){
    echo 'не верны параметры объекта';
} else {
    $controller = new Controller();
    if (empty($mode)) echo 'не задан режим отображения';
    elseif ($mode == 'all') $controller->getStatusDisp($id_obj);
    elseif ($mode == 'one') $controller->getStatusDetail($id_obj);
    unset ($controller);
}
?>
