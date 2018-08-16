<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OperObserver 
 * @version 1.1.0
 * @author Ashuika Mikalai aka grajdanin
 * @e-mail ashuikam(at)gmail.com
 */
class OperObserver implements IObserver {
    private $sqlQuery;
    function __construct() {
        $this->sqlQuery = SQLQuerySingleton::getInstance();
    }

    public function onChanged($sender, $args) {
        $this->setOper($sender, $args);
    }
    // метод обрабатывает все параметры полученнные от объекта наблюдения
    // Затем анализирует в соответсвии с заданными триггерами (ttriggers)
    // затем пишет результат в виде массива в (toper) и (talarms)
    private function setOper($sender, $args) {
        try {
            foreach ($args as $param => $value) {
                // очистка выходного массива
                $oper = array();
                // сброс триггера
                $trigger = FALSE;
                $oper['value'] = $value;
                // получает id (текущего параметра) из справочника параметров
                $oper['id_param'] = $this->sqlQuery->getKey($param);
                if (empty($oper['id_param'])) $oper['id_param'] = $this->sqlQuery->getKey($oper['value']);
                // В случае если параметр или значение что касается трепов не 
                // описан в справочнике spcontrol он просто пропускается
                if(!empty ($oper['id_param'])){    
                    // id объекта
                    $oper['id_obj'] = $sender;
                    // первончальным статусом аварии всегда является её отсутствие
                    // Сброс статуса !
                    $oper['alarmst'] = 0;
                    // сброс значения аварии !
                    $oper['alarm'] = 1;
                    // получает триггер , если тот установлен на данный параметр
                    $trigger = $this->sqlQuery->getTriggers($oper['id_obj'],$oper['id_param']);
                    if (isset($trigger) && $trigger){
                        // анализ, если триггер установлен
                        $oper = $this->compareParametrs($oper+$trigger[0]);
                        // текущий аварийный статус параметра из (toper)
                        ///!!!!!!!!!!!!!!!!!!!!if($trigger[0]['custom_alarm'] != 'no') $oper['alarm'] = $trigger[0]['custom_alarm'];
                        $operAlarmSt = $this->sqlQuery->getOperAlarmst($oper['id_obj'],$oper['id_param']);
                        if ($oper['alarmst'] && !$operAlarmSt){ 
                            $oper['id_alarm_ok'] = 0;
                            $oper['approved'] = 0;
                            $this->sqlQuery->alarmsWrite($oper);
                        }
                            elseif (!$oper['alarmst'] && $operAlarmSt) {
                                $oper['approved'] = 1;
                                $oper['alarm'] = 9;
                                $oper['id_alarm_ok'] = $this->sqlQuery->getIdLastAlarm($oper['id_obj'],$oper['id_param']);
                                $this->sqlQuery->alarmsWrite($oper);
                                $this->sqlQuery->alarmsUpdate($oper['id_alarm_ok']);
                        }
                    }
                    $this->sqlQuery->setConditionDevice($oper);
                } else throw new Exception("$param не контролируется");
            }
        } catch (Exception $errors) {
            $args['errors'] = $errors->getMessage();
            //$this->onChanged("system", $args);
        }
    }

    // метод формирует аварию, по условиям ненормальной работы устройства
    // т.е. необходимо указывать аварийные параметры объекта
    private function compareParametrs($oper){
        switch ($oper['trigger']) {
            //Авария формируется, если текущее значение параметра вошло в заданный диапазон
            case "within":
                if ($oper['value'] < $oper['value2'] || $oper['value'] > $oper['value1']){
                    $oper['alarm'] = 2;
                    $oper['alarmst'] = 1;
                }
                break;
            //Авария формируется, если текущее значение параметра вышло из заданного диапазона
            case "abroad":
                if ($oper['value'] > $oper['value2'] || $oper['value'] < $oper['value1']){
                    $oper['alarm'] = 3;
                    $oper['alarmst'] = 1;
                }
                break;
            //Авария формируется, если текущее значение параметра больше заданного
            case "greater":
                if ($oper['value'] >= $oper['value1']){
                    $oper['alarm'] = 4;
                    $oper['alarmst'] = 1;
                }
                break;
            //Авария формируется, если текущее значение параметра меньше заданного
            case "isless":
                if ($oper['value'] <= $oper['value1']){
                    $oper['alarm'] = 5;
                    $oper['alarmst'] = 1;
                }
                break;
            //Авария формируется, если текущее значение параметра равно заданному
            case "equally":
                if ($oper['value'] == $oper['value1']){
                    $oper['alarm'] = 6;
                    $oper['alarmst'] = 1;
                }
                break;
            //Авария формируется, если текущее значение параметра не равно заданному
            case "noequally":
                if ($oper['value'] != $oper['value1']){
                    $oper['alarm'] = 7;
                    $oper['alarmst'] = 1;
                }
                break;
            default:
                $oper['alarm'] = 8;
                $oper['alarmst'] = 1;
                break;
        }
        return $oper;
    }
    protected function getTrap(){
        return "аварийное значение";
    }

}

?>
