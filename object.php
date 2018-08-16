<?php
require "common.php";
$id_obj = $_GET['id_obj'];
if (empty($id_obj)){
    echo 'не верны параметры объекта';
} else {
    $controller = new Controller();
    $controller->getDeviceStatusAndAlarms($id_obj);
    unset ($controller);
}
?>
