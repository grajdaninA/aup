<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryFactory
 *
 * @author grajdanin
 */

class QueryConstructor {
    private $filter, $connectSingleton;
    function  __construct() {
        $this->filter = new Filters();
        $this->connectSingleton = ConnectSingleton::getInstance();
        $this->connectSingleton -> dbConnect(DB_LOGIN, DB_PASSWD, DB_HOST, DB_NAME);
        $this->connectSingleton -> execute("SET NAMES utf8");
    }
    public function getKeysForFilters($sqlmask, $datakey){
        $this->filter -> getSqlMask($sqlmask);
        $this->filter -> getSystemKeys($datakey);
    }

    protected function insertTab($table,$_pole) {
        $_pole = $this->filter->dataFilter($_pole);
        $sql = "INSERT into `$table` (";
        foreach ($_pole as $key => $value){
            $sql .= $key . ", ";
        }
        $sql .= ") values ('";
        foreach ($_pole as $key => $value) {
            $sql .= $value . "', '";
        }
        $sql .= ")";
        $sql = $this->filter -> sqlFilter($sql);
        return $this->connectSingleton->execute($sql);
        }

      protected function selectTab($table, $pole = '*') {
          $sql = "SELECT $pole from `$table` ORDER BY `id`";
          return $this->connectSingleton->execute($sql);
      }
      protected function uniQuery($sql){
          return $this->connectSingleton->execute($sql);
      }
      protected function arrayQuery($sql){
          $array = false;
          $sql = $this->filter -> sqlFilter($sql);
          $query = $this->connectSingleton->execute($sql);
          $i = 0;
          while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
              $array[$i] = $row;
              $i++;
          }
       return $array;
      }
      protected function updateTab($table, $data, $where) {
          $sql = "UPDATE `$table` SET ";
          foreach ($data as $key => $value) {
              $sql .= "$key = '$value', ";
          }
          $sql .="$where";
          $sql = $this->filter -> sqlFilter($sql);
          return $this->connectSingleton->execute($sql);
      }
}
?>
