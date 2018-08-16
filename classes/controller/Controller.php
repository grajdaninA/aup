<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Ashuika Mikalai aka grajdanin
 * @e-mail ashuikam@gmail.com
 */
class Controller {
    private $sqlQuery, $view;
    function __construct() {
        $this->sqlQuery = SQLQuerySingleton::getInstance();
        $this->view = new View();
    }
    /* the getStatusDisp method returns the page containing: 
     * name of object of supervision,the emergency status and existence of 
     * unaccepted accidents for the dispatcher
     */
    public function getStatusDisp($id_obj){
        $statusobj = $this->sqlQuery->getOperStatusObj($id_obj);
        $template = $this->view->getTemplate('object');
        $page['color'] = 'springgreen';
        $page['state'] = 'НОРМА';
        if ($this->sqlQuery->getNoAppAlarms($id_obj,TRUE)) $page['disp'] = 
                'есть непринятые аварии';
        else $page['disp'] = '';
        if (is_array($statusobj)){
            foreach ($statusobj as $value) {
                if (isset($value['obj'])) $page['obj'] = $value['obj'];
                if ($value['alarmst']) {
                    $page['color'] = 'red';
                    $page['state'] = 'АВАРИЯ';
                }
            }
        }
        $page['object'] = $this->view->parseTemplate($template, $page);
        $this->view->display($page['object']);
    }
    /* the getStatusDisp method returns the page ($print = TRUE) 
     * or array ($print = FALSE) containing:
     * the name of object of supervision, value of controlled parameters, 
     * the emergency status of each of the parameters
     */
    public function getStatusDetail($id_obj, $print = TRUE){
        $oper = $this->sqlQuery->getStatusParamObj($id_obj);
        $template_alarms = $this->view->getTemplate('alarm');
        $page['alarms'] = '';
        $page['id_obj'] = $id_obj;
        if (is_array($oper)){
            foreach ($oper as $value) {
                if ($value['alarmst']) $value['dcolor'] = 'red';
                else $value['dcolor'] = 'green';
                if (isset($value['obj'])) $page['obj'] = $value['obj'];
                if (isset($value['value']) && isset($value['func'])){
                    $value['value'] = $this->transFunc($value['func'],
                    $value['value']);
                }
                $page['alarms'] .= $this->view->parseTemplate($template_alarms, 
                        $value)."<br/>";
            }
        }
        if ($print) $this->view->display ($page['alarms']);
        else return $page;
    }
    /* the getNoApprovedAlarms method returns the page ($print=TRUE)
     * or array ($print = FALSE) containing all not approved alarms, and
     * form for approval by their operator
     */
    public function getNoApprovedAlarms($id_obj, $print = TRUE){
        $no_app_alarms = $this->sqlQuery->getNoAppAlarms($id_obj);
        if (is_array($no_app_alarms)){
            $template_noapp = $this->view->getTemplate('noappalarms');
            $template_noapptable = $this->view->getTemplate('noapptable');
            $page['noapptable'] = '';
            $page['id_obj'] = $id_obj;
            $switch = TRUE;
            foreach ($no_app_alarms as $value) {
                if($switch) $value['trcolor'] = 'grey';
                else $value['trcolor'] = 'lightgrey';
                $switch = ($switch xor TRUE);
                if($value['id_alarm_ok'] > 0) $value['status'] = 'устранена';
                else $value['status'] = 'текущая';
                $value['approved'] = 'enable';
                $page['noapptable'] .= $this->view->
                        parseTemplate($template_noapptable, $value);
            }
            $page['no_approved_alarms'] = $this->view->parseTemplate($template_noapp, $page);
        } else {
            $page['no_approved_alarms'] = 'все аварии приняты диспетчером';
        }
        if($print) $this->view->display ($page['no_approved_alarms']);
        else return $page;
    }
    /*
     * the "dispetcher" method prepares the page-grid for an otorazheniye of 
     * a condition of objects
     */
    public function dispetcher(){
        $page = "";
        $snmpObj = $this->sqlQuery->getSNMPObj();
        if(is_array($snmpObj)){
            foreach ($snmpObj as $value) {
                $template = $this->view -> getTemplate('monitor_center');
            $page .= $this->view->parseTemplate($template, $value);
            }
        }
        return $this->view -> display($page);
    }
    /* the getDeviceStatusAndAlarms method returns the page containing:
     * the name of object of supervision, value of controlled parameters, 
     * the emergency status of each of the parameters,
     * unconfirmed accidents if is for confirmation by the dispatcher
     */

    public function getDeviceStatusAndAlarms($id_obj) {
        $page = array();
        $template = $this->view->getTemplate('object_param_alarm');
        $page += $this->getStatusDetail($id_obj, FALSE);
        $page += $this->getNoApprovedAlarms($id_obj, FALSE);
        $page['ObjParamAlarms'] = $this->view->parseTemplate($template, $page);
        return $this->view->display($page['ObjParamAlarms']);
    }
    /*  the "transFunc" method doesn't support more than one arithmetic operation, 
     *  doesn't transform more than one variable, also makes only operations 
     *  listed below: addition, subtraction, multiplication, division.
     *  format $func:
     *          v[+-/*]number
     *  example: v+15224
    */
    protected function transFunc($func, $value){
        if($func != 'no'){
            $pattern = '/v([\*\/\+\-])(\d+)/';
            preg_match($pattern, $func, $arg);
            switch ($arg[1]) {
                case '+':   return $value + $arg[2]; 
                case '-':   return $value - $arg[2];
                case '/':   return $value / $arg[2];
                case '*':   return $value * $arg[2];

                default:
                    return $value;
            }
        } else return $value;
    }
    /*  the "approved" method confirms viewing of dispatchers the chosen accidents
     */
    public function approvedAlarms($array){
        if (is_array($array)){
            foreach ($array as $id => $value){
                if($value == 'on')
                $this->sqlQuery->approvedAlarm($id);
            }
        } else return 'не заданы аварии для подтверждения';
    }
}

?>
