<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLQuery
 *
 * @author Ashuika Mikalai aka grajdanin
 * @e-mail ashuikam(at)gmail.com
 */
class SQLQuerySingleton extends QueryConstructor implements ISingleton {
    static private $instance = NULL;
    public function __construct() {
        parent::__construct();
    }
    static public function getInstance() {
        if (self::$instance == null){
            self::$instance = new SQLQuerySingleton();
        }
        return self::$instance;;
    } 
    public function getOperStatus() {
        $result = $this->selectTab('toper');
        return $result;
    }
    public function getSNMPObj(){
        return $this->arrayQuery('SELECT * FROM tobject WHERE `active` = TRUE ORDER by `id`');
    }
    public function getSNMPObjandORIDs(){
        return $this->arrayQuery('SELECT tobject.id, tobject.obj, tobject.ip,
                                  tobject.comm, tobject.control, spsysORIDs.ORID
                                  FROM tobject LEFT JOIN spsysORIDs ON
                                  tobject.id_ORID = spsysORIDs.id WHERE
                                  tobject.active = TRUE ORDER by tobject.id');
    }

    public function getMibs(){
        return $this->arrayQuery('SELECT file FROM spmibs');
    }
    public function getControl($control) {
        $control = explode(',', $control);
        foreach ($control as $value) {
            $tmp = $this->arrayQuery("SELECT param FROM spcontrol WHERE id=$value");
            $newcontrol[] = $tmp[0]['param'];
        }
        return $newcontrol;
    }
    public function getKey($key) {
        $sql = "SELECT `id` FROM `spcontrol` WHERE param='$key'";
        $key = $this->arrayQuery($sql);
        return $key[0]['id'];
    }
    public function setConditionDevice($oper){
        $sql_delete = "DELETE FROM `toper` WHERE `id_param`= 5 AND `id_obj` = ".$oper['id_obj'];
        $sql_delete2 = "DELETE FROM `toper` WHERE `id_param`<> 5 AND `id_obj` = ".$oper['id_obj'];
        if ($oper['id_param'] != 5){
            $this->uniQuery($sql_delete);
        } else {
            $this->uniQuery($sql_delete2);
        } 
        $sql_ins = "INSERT into `toper` (id_obj, id_param, value, alarmst, id_alarm)
                    values (".$oper['id_obj'].",".$oper['id_param'].",
                    '".$oper['value']."',".$oper['alarmst'].",'".$oper['alarm']."')";
        $sql_upd = "UPDATE `toper` set value = '".$oper['value']."', 
                    alarmst = ".$oper['alarmst'].", id_alarm = '".$oper['alarm']."' WHERE id_obj = 
                   ".$oper['id_obj']." AND id_param = ".$oper['id_param'];
        $sql = "SELECT * FROM `toper` WHERE id_obj = ".$oper['id_obj']." AND id_param = ".$oper['id_param'];
        
        if (!$this->arrayQuery($sql) === TRUE){  
            $sql = $sql_ins;
        }
        else $sql = $sql_upd;
        $this->uniQuery($sql);
    }
    private function eraseOper() {
        $sql = "TRUNCATE TABLE `toper`";
        $this->uniQuery($sql);
    }
    public function addMibs($param) {
        $this->insertTab('spmibs', $param);
    }
    public function addSNMPObject($param){
        $this->insertTab('tobject', $param);
    }
    public function getSNMPObjIds(){
        $sql = "SELECT id FROM tobject WHERE `active` = TRUE";
        $result = $this->arrayQuery($sql);
        foreach ($result as $value) {
            $array[] = $value['id'];
        }
        return $array;
    }
    public function getOperStatusObjWithTriggers($id_obj) {
        $sql = "SELECT toper.value,ttriggers.value1, ttriggers.value2, 
                sptriggers.trigger FROM `toper` 
                LEFT JOIN `ttriggers` ON toper.id_obj = ttriggers.id_obj 
                AND toper.id_param = ttriggers.id_param 
                LEFT JOIN `sptriggers` ON ttriggers.id_trigger = sptriggers.id 
                WHERE toper.id_obj = $id_obj";
        return $this->arrayQuery($sql);
    }
    public function getTriggers($id_obj, $id_param) {
        $sql = "SELECT ttriggers.value1, ttriggers.value2, sptriggers.trigger,
            ttriggers.id_alarm FROM `ttriggers` LEFT JOIN `sptriggers` 
            ON ttriggers.id_trigger = sptriggers.id 
            WHERE ttriggers.id_obj = $id_obj AND ttriggers.id_param = $id_param";
        return $this->arrayQuery($sql);
    }
    public function alarmsWrite($oper){
        $sql = "INSERT into `talarms` (id_obj, id_param, value, id_alarm, date, 
            time, approved, id_alarm_ok)
            values (".$oper['id_obj'].", ".$oper['id_param'].", '".$oper['value']."',
            '".$oper['alarm']."', CURRENT_DATE( ), CURRENT_TIME( ), "
              .$oper['approved'].", ".$oper['id_alarm_ok'].")";
        $this->uniQuery($sql);
    }
    public function alarmsUpdate($id){
        $sql = "UPDATE `talarms` SET id_alarm_ok = LAST_INSERT_ID()
            WHERE id = $id AND id_alarm_ok = 0";
        return $this->uniQuery($sql);
    }

    public function getOperStatusObj($id_obj){
        $sql = "SELECT toper.alarmst, tobject.obj FROM `toper` LEFT JOIN
                `tobject` ON toper.id_obj = tobject.id 
                WHERE id_obj = $id_obj";
        return $this->arrayQuery($sql);
    }

    public function getStatusParamObj($id_obj){
        $sql = "SELECT toper.value, toper.alarmst, spalarms.alarm_descr, tobject.obj, 
            spcontrol.param_descr, spcontrol.func FROM `toper` LEFT JOIN `tobject` ON 
            toper.id_obj = tobject.id LEFT JOIN `spcontrol` ON
            toper.id_param = spcontrol.id LEFT JOIN `spalarms` ON 
            toper.id_alarm = spalarms.id WHERE id_obj = $id_obj";
        return $this->arrayQuery($sql);
    }
    public function getOperAlarmst($id_obj, $id_param){
        $sql = "SELECT toper.alarmst FROM toper WHERE 
            id_obj = $id_obj AND id_param = $id_param";
        $result = $this->arrayQuery($sql);
        if (is_array($result))
            return $result[0]['alarmst'];
        else return $result;
    }
    public function getNoAppAlarms($id_obj, $bool = FALSE){
        $sql = "SELECT talarms.*, spcontrol.param_descr FROM talarms LEFT JOIN
            spcontrol ON talarms.id_param = spcontrol.id 
            WHERE id_obj = $id_obj AND approved = 0 ";
        if ($bool) return is_array($this->arrayQuery($sql));
        else return $this->arrayQuery($sql);
    }
    public function getIdLastAlarm($id_obj, $id_param){
        $sql = "SELECT talarms.id FROM talarms WHERE 
            id_obj = $id_obj AND id_param = $id_param ORDER BY id DESC LIMIT 0,1";
        $id = $this->arrayQuery($sql);
        return $id[0]['id']; 
        
    }
    public function approvedAlarm($id){
        $sql = "UPDATE talarms SET approved = 1 WHERE id = $id";
        return $this->uniQuery($sql);
    }
    
}

?>
