<?php
require 'common.php';
$array = $_POST;
$controller = new Controller();
if($array['mode'] == 'approved'){
    $controller = new Controller();
    $controller->approvedAlarms($array);
}
$controller->getNoApprovedAlarms($array['id_obj']);
unset($controller);

?>
