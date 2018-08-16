<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Filters
 *
 * @author grajdanin
 */
class Filters {
    private $system_key, $replacement, $pattern;
    function getSystemKeys($array){
        $this -> system_key = $array;
    }
    // метод оставляет сисетмные ключи
    function dataFilter($array) {
        return array_diff_key($array, $this -> system_key);
    }
    function getSqlMask($sqlmask){
        $this -> pattern = $sqlmask['pattern'];
        $this -> replacement = $sqlmask['replacement'];
    }
    function sqlFilter($sql){
        return preg_replace($this->pattern, $this->replacement, $sql);
    }

}
?>
