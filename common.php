<?php
require 'config.php';

//set_include_path(ABS_WAY. PATH_SEPARATOR . 
//                 ABS_WAY."classes". PATH_SEPARATOR .
//                 ABS_WAY."classes/model". PATH_SEPARATOR .
//                 ABS_WAY."classes/controller". PATH_SEPARATOR .
//                 ABS_WAY."classes/view". PATH_SEPARATOR .
//                 ABS_WAY."templates". PATH_SEPARATOR .    
//                    ".");
//
//function __autoload($classname) {
//    include_once "$classname.php";
//}

$path = get_include_path();
spl_autoload_register(function ($classname) {
        include_once "$classname.php";
});
//$objects['queryFactory'] = new QueryFactory($system_key['sqlmask'],$system_key['datakey']);
//$objects['snmpQF'] = new SNMPQueryFactory($queryFactory->arrayQuery('SELECT file FROM spmibs'));
$sqlQuery = SQLQuerySingleton::getInstance();
$sqlQuery->getKeysForFilters($system_key['sqlmask'], $system_key['datakey']);
//$sqlQuery = SQLQuery::getInstance($system_key['sqlmask'], $system_key['datakey']);


?>
