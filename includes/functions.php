<?php
/**
 * Created by
 * User: Маром
 * Date: 21.12.14
 * Time: 0:15
 */

defined('_YRNEXEC') or die;

class yrn {
    public $com = '';//компонент
    public $task = '';//задача
    public $params = array();//параметры
    public $time_start = 0.0;
    public $time_end = 0.0;


    public function end() {//функция завершения работы php кода
        global $db, $log;
        $log->end();//отправляем логи в БД
        $db->close();//закрываем соединение с БД
    }
}

$app = new yrn();
$app->time_start = (float) $time_start;

date_default_timezone_set('UTC');
define('DATETIMEFORMAT','Y-m-d H:i:s');
define('DATEFORMAT','Y-m-d');
require_once(YRNPATH_BASE.'/yrn-config.php');

$config = new YRNConfig();

//TODO: get from database
$config->offline_ip[]='195.82.156.229';//работа
//$config->offline_ip[]= '5.128.145.60';
$config->offline_ip[]= '5.44.168.22';


$debug = array();
require_once(YRNPATH_BASE.'/includes/debug.php');

$err = array();//TODO: Класс ошибок - это класс alerts

function convertDate($str) {
    //$str_date = "2001-01-01 15:20:20";
    $date_elems = explode(" ",$str);
    $date = explode("-", $date_elems[0]);
    $time = explode(":", $date_elems[1]);
    $result =  mktime($time[0], $time[1],$time[2], $date[1],$date[2], $date[0]);
    return $result;
}

require_once(YRNPATH_BASE.'/includes/alerts.php');

# Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

require_once(YRNPATH_BASE.'/includes/email.php');

require_once(YRNPATH_BASE.'/includes/database.php');//TODO: вынести БД в класс
    /*mysql_connect("localhost", "myhost", "myhost");
    mysql_select_db("testtable");
    */
    /*$mysqli = new mysqli($config->db_host, $config->db_user, $config->db_pass, $config->db_base);//TODO: удалить
    if ($mysqli->connect_errno) {
        $err[] = "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    $mysqli->set_charset('utf8'); //http://php.net/manual/ru/mysqlinfo.concepts.charset.php
    //$mysqli->set_opt('session.time_zone','+00:00');
    $mysqli->query("SET time_zone='+00:00'");*/
    //echo $mysqli->host_info . "\n";

/*function mysql_end($mysqli) {
    $mysqli->
}*/

require_once(YRNPATH_BASE.'/includes/url.php');

require_once(YRNPATH_BASE.'/includes/links.php');

require_once(YRNPATH_BASE.'/includes/templates.php');

require_once(YRNPATH_BASE.'/includes/menu.php');

require_once(YRNPATH_BASE.'/includes/users.php');
//uses: $config,$mysqli, $alerts

require_once(YRNPATH_BASE.'/includes/languages.php');
//uses:



require_once(YRNPATH_BASE.'/includes/logs.php');



function exec_end() {//TODO: удалить
    global $mysqli, $log;
    $log->end();//отправляем логи в БД
    $mysqli->close();//закрываем соединение с БД

}