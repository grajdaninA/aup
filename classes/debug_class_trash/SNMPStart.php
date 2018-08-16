<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SNMPFactory
 *
 * @author grajdanin
 */
class SNMPStart {
    private $objects = array(), $snmpObj = array();
    private $snmpQuery;
    function __construct() {
        $this->sqlQuery = SQLQuerySingleton::getInstance();
        $this->inicialSNMPQuery();
    }
    function startMonitoring(){
 //       $this->snmpQuery->
//        $snmpObj = $this->sqlQuery->getSNMPObj();
        
//        foreach ($snmpObj as $obj) {
//            //$obj['control'] = $this->sqlQuery->getControl($obj['control']);
//            $this->snmpQuery->getParam($obj);
//        }    
    }
    private function inicialSNMPQuery() {
        //$this->mibs = $this->sqlQuery->getMibs();
        $this->snmpQuery = new SNMPQueryObservable();
        $this->snmpQuery->addObserver(new OperObserver());
    }
    private function killSNMPQuery(){
        unset ($this->snmpQuery);
    }
}

?>
