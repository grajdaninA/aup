<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author grajdanin
 */

class View {
    function setTagSelect($query) {
        $array='';
        while($row=mysql_fetch_array($query)){
            $array[$row[0]]= $row[1];
        }
        return $array;
    }
    function printTagSelect($name, $options, $id='') {
        if (isset($name) && isset($options)){
            
            $select = "<select name='$name' id='$id'>";
            foreach ($options as $value) {
                $select .= "<option value='$value'>$value</option>";
            }
            $select .= "</select>";
            return $select;
        } die ('не заданы параметры тега Select');
    }
    function parseTemplate($template, $text_array) {
        
        return preg_replace('/\{([0-9a-z\-_]*?)\}/Ssie', '( (isset($text_array[\'\1\']) ) ? $text_array[\'\1\'] : \'\' );', $template);
        
    }
    function getTemplate($template, $ex=TEMPLATE_EX){
        $filename = TEMPLATE_DIR . '/' . $template . '.' . $ex;
        return file_get_contents($filename);
    }
    function display($page) {
        echo $page;
    }
    function printTagTable($array, $options='no'){
        $table="";
        foreach ($array as $value){
            $table.="<tr>";
            foreach ($value as $key1=>$value1) {
                    $table.="<td>".$value1."</td>";
            }
            if ($options != 'no'){
                $table.="<td>".$this->printTagSelect('id_nick_'.$key1, $options)."</td></tr>";
            } else {
                $table.="</tr>";
            }
        }
        return $table;
    }
}
?>
