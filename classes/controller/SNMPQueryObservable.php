<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SNMPQueryObservable ver 1.0.1 beta
 *
 * @author grajdanin
 */
class SNMPQueryObservable implements IObservable{
    protected $_observers = array (), $errors = '', $mibs = array(), 
            $SNMPobj = array(), $sqlQuery;
    
            function __construct() {
        try {
            $this->sqlQuery = SQLQuerySingleton::getInstance();
            $this->mibs = $this->sqlQuery->getMibs();
            foreach ($this->mibs as $filename) {
                if (!snmp_read_mib(MIB.$filename['file'])) 
                throw new Exception("Не найден файл MIB : $filename </br>");
            }
        } catch (Exception $errors) {
            $errors .= $errors->getMessage() . '';
            $this->commonObservers('system-rcc', FALSE, $errors);
        }
    }
    public function addObserver($observer) {
        $this->_observers [] = $observer;
    }
    public function startMonitoring(){
        $this->SNMPobj = $this->sqlQuery->getSNMPObjandORIDs();
        foreach ($this->SNMPobj as $obj) {
            $answSnmp = $this->getParam($obj);
            $this->commonObservers($obj['id'], $answSnmp);
        }
    }

    public function getParam($object) {
        try {
            $errors = FALSE;
            $ping = exec("ping -c 1 ".$object['ip'],$output);
            if ($this->hostAvalible($output)){
                $control = $this->sqlQuery->getControl($object['control']);
                $answ = snmprealwalk($object['ip'], $object['comm'], $object['ORID']);
                foreach ($answ as $key => $value) {
                    $answ[$key] = $this->transValue($value);
                }
                $answSnmp = array_intersect_key($answ, array_flip($control))+  
                            array_intersect($answ, $control);
            } else throw new Exception("не доступен");
        } catch (Exception $errors) {
        $answSnmp['errors'] = $errors->getMessage() . '';
        }
        return $answSnmp;
    }
    // метод преобразовывает значение параметра полученнное от SNMP объекта,
    // отбрасывая мусор, напрмер тип и двоеточие:
    // (INTEGER: 55100)-> (55100)
    
    private function transValue($value) {
        $pattern = '/(^[a-zA-Z0-9]+\:)\s([a-zA-Z0-9\"\-\(\),\:\.\s]+)(\s*)$/';
        $replacement = "\\2";
        $value = preg_replace($pattern, $replacement, $value);
        return $value;
    }
    public function getErrors(){
        return $this->errors;
    }
    protected function commonObservers($sender, $args) {
        foreach ($this->_observers as $obs){
            $obs->onChanged($sender, $args);
        }
    }
    protected function hostAvalible($param) {
        foreach ($param as $value) {
            if(strpos($value, '100% packet loss')) return FALSE;
        }
        return TRUE;
    }
   

}

?>
