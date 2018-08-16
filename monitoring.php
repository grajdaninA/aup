<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'common.php';
$snmpMon = new SNMPQueryObservable();
$snmpMon->addObserver(new OperObserver());
$snmpMon->startMonitoring();
unset($snmpMon);

?>
