<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'common.php';
//snmp_read_mib(MIB.'NetSure_ESNA.mib');
snmp_read_mib(MIB.'NetSure-SCU-Plus.mib');
$param1 = snmprealwalk('10.4.70.178', 'public', 'EES-POWER-MIB');
$param2 = snmpget('10.4.70.178', 'public', 'systemVoltage.0');
echo $param;
// $a = snmptable("10.4.70.178", "public", "powerMIB") or die("error");
//    print_r($a);
//    $a = snmptable("10.4.69.5", "public", "powerMIB") or die("error");
//    print_r($a);
//
//function snmptable($host, $community, $oid) {
//    // TODO: get original state and restore at bottom
//    snmp_set_oid_numeric_print(TRUE);
//    snmp_set_quick_print(TRUE);
//    snmp_set_enum_print(TRUE); 
//
//    $retval = array();
//    $raw = snmprealwalk($host, $community, $oid) or die("snmptable: unable to walk OID $oid");
//
//    $prefix_length = 0; 
//
//    foreach ($raw as $key => $value) {
//        if ($prefix_length == 0) {
//            // don't just use $oid's length since it may be non-numeric
//            $prefix_elements = count(explode('.',$oid));
//            $tmp = '.' . strtok($key, '.');
//            while ($prefix_elements > 1) {
//                $tmp .= '.' . strtok('.');
//                $prefix_elements--;
//            }
//            $tmp .= '.';
//            $prefix_length = strlen($tmp);
//        }
//        $key = substr($key, $prefix_length);
//        $index = explode('.', $key, 2);
//        isset($retval[$index[1]]) or $retval[$index[1]] = array();
//        isset($firstrow) or $firstrow = $index[1];
//        $retval[$index[1]][$index[0]] = $value;
//    }
//
//    // check for holes in the table and fill them in
//    foreach ($retval[$firstrow] as $key => $tmp) {
//        foreach($retval as $check => $tmp2) {
//            if (! isset($retval[$check][$key])) {
//                $retval[$check][$key] = '';
//            }
//        }
//    }
//
//    return($retval);
//}
?>
