<?php
include 'common.php';
class A extends Controller{
    function c ($array, $v){
        foreach ($array as $func) {
            $result[] = $this->transFunc($func, $v);
        }
        return $result;
    }

}
$array = array('v/1000','v*1000','v+1000','v-1000');
$v=56000;
$test = new A();
$bla = $test->c($array, $v);
//$test->getDeviceStatusAndAlarms($id_obj);
print_r($bla);


?>
