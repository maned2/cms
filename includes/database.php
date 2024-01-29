<?php
/**
 * User: Маром
 * Date: 12.06.15
 * Time: 18:58
 */

defined('_YRNEXEC') or die;

$mysqli ='';

class database1 {

    public $connected = false;//соединились или нет
    public $querycount = 0;
    public $querylist = array();
    private $mysqli;


    function connect() {
        global $config;
        $this->mysqli = new mysqli($config->db_host, $config->db_user, $config->db_pass, $config->db_base);
        if ($this->mysqli->connect_errno) {
            $err[] = "Не удалось подключиться к MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }
        $this->mysqli->set_charset('utf8'); //http://php.net/manual/ru/mysqlinfo.concepts.charset.php
        //$mysqli->set_opt('session.time_zone','+00:00');
        $this->mysqli->query("SET time_zone='+00:00'");
        $this->connected = true;
    }

    function query($string) {
        global $deb;
        if (!$this->connected) {
            $this->connect();
        }
        $this->querycount++;
        $this->querylist[] = $string;
        $deb->ads($this->querycount.': '.$string,'database');
        $data = $this->mysqli->query($string);
        return $data;
    }

    function real_escape_string($string) {
        return $this->mysqli->real_escape_string($string);
    }

    function close() {

        if ($this->connected)
            $this->mysqli->close();
    }

}

$db = new database1;