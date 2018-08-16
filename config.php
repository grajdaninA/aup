<?php
ini_set('display_errors', 1);
ini_set('max_execution_time', 360);
ini_set('memory_limit', '128M');
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '10M');

//define('ABS_WAY',      '/srv/www/www/debugrcc/RCC-1.0/');
//define('UPLOADEDFILES', ABS_WAY.'files/');
//define('REPORTS', ABS_WAY.'reports/');
//define('TEMPLATE_DIR', 'templates/');
//
//define('TEMPLATE_EX',  'html');
//
//
//define('MIB',  ABS_WAY.'mibs/');

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);

define('ABS_WAY',      DS. 'var' .DS. 'www' .DS. 'mgb');
define('TEMPLATE_DIR', 'templates' .DS );
define('CLASSES', 'classes');
define('MODEL', CLASSES .DS. 'model');
define('CONTROLLER', CLASSES .DS. 'controller');
define('VIEW', CLASSES. DS. "view");
define('TEMPLATE_EX',  'html');
define('TRASH', ABS_WAY .DS. 'trash'.DS );

set_include_path(CLASSES .PS. CONTROLLER .PS. MODEL .PS. VIEW .PS. ".");

define('DB_NAME',      'phpmyadmin');
define('DB_LOGIN',     'phpmyadmin');
define('DB_PASSWD',    '');
define('DB_HOST',      'localhost');


$system_key = array(
    'datakey' => array(
                        'mode'     =>     'mode',
                        'tpl'      =>     'tpl',
                        'newtpl'   =>     'newtpl',
                        'passw2' => 'passw2'
                      ),
    
    'sqlmask' => array(
                        'pattern'       => array('/,\s\)/', '/\',\s\'\)/', '/,\sWHERE/'),
                        'replacement'   => array(')', '\')',' WHERE'),
    ),
    'anitafilter' => array(
                        'pattern'       => '/\D/',
                        'replacement'   => ' '
    ),
     'traficparser' => array(
        '/(\w{1,6}O)\s{1,10}(\d{1,3}\.\d)\s{1,8}\d{1,4}\s{1,8}(\d{1,3}\.\d)\s{1,8}(\d{1,4})\s{1,8}(\d{1,2}\.\d)\s{1,8}\d{1,4}\.\d\s{1,8}\d{1,4}/',
        '/(\w{1,6}I)\s{1,10}(\d{1,3}\.\d)\s{1,8}\d{1,4}\s{1,11}(\d{1,4})\s{1,8}(\d{1,2}\.\d)\s{1,8}\d{1,4}\.\d\s{1,8}\d{1,4}/'
    ),
    'week' => array(
        1 => 'понедельник',
        2 => 'вторник',
        3 => 'среду',
        4 => 'четверг',
        5 => 'пятницу',
        6 => 'субботу',
        7 => 'воскресенье'
    ),
    'time' => array (
        'begin' => '20',
        'end'   =>  '21'
    )

);

$lang['system'] = '';
$lang['report_file'] = 'set_trafic.php';
?>
