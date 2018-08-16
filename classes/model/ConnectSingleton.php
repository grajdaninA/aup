<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConnectFactory
 *
 * @author Ashuika Mikalai aka grajdanin
 * e-mail : ashuikam@gmail.com
 */
class ConnectSingleton implements ISingleton{
    static private $instance = null;
    private $connect;
    function dbConnect($user, $password, $dbhost, $db){
        $this->connect = new mysqli($dbhost, $user, $password, $db) or 
                die("DB not connected" . $this->connect->connect_error);        
    }

    function execute($sql){
        $result = $this->connect->query($sql) or 
                die ('Ошибка : ' . $this->connect->error);
        return $result;
    }
    function dbDisconnect() {
        $this->connect->close();
    }
    static public function getInstance(){
          if (self::$instance == null){
              self::$instance = new ConnectSingleton;
          }
          return self::$instance;
      }
}
?>
