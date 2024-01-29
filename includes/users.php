<?php
/**
 * Created by
 * User: Маром
 * Date: 21.12.14
 * Time: 0:15
 */
defined('_YRNEXEC') or die;

class usr {

    public $is_browser = False;
    public $is_mobile = False;
    public $is_robot = False;

    public $browsers = array();
    public $operating_systems = array();
    public $mobiles = array();
    public $robots = array();

    public $ip = '';
    public $version = '';
    public $browser = '';
    public $browser_full_name = '';
    public $operating_system = '';
    public $os_version = '';
    public $robot = '';
    public $mobile = '';

    public $hash = '';
    public $remote_addr = '';
    public $id = ''; //id пользователя
    public $session_id = 0;//id сессии
    public $user_group = 0;
    public $agent = '';
    public $lang = '';
    public $lang_full = '';
    public $languages = array();//Массив языков, которые знает пользователь
    public $is_auth = false;

    public $avatar = ''; //аватар пользователя

    public $login = '';

    private $access = array();

    public function init() {
        global $db,$config,$mysqli,$alerts;

        // Загружаем массивы для работы с данными
        $files = array('browsers', 'operating_systems', 'mobiles', 'robots');
        foreach($files as $file) {
            $this->load( $file );
        }

        // Данные пользователя
        $this->agent = (@$_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : '';
        // Вызываем методы для заполнения данных пользователя
        $setMethods = array('set_ip', 'set_lang', 'set_browser', 'set_operating_system', 'set_robot', 'set_mobile');
        foreach($setMethods as $method) {
            $this->$method();
        }
        $this->user_group=0;//- это гость

        if (isset($_COOKIE['hash'])) {//есть кука хеша
            $this->hash = htmlspecialchars($_COOKIE['hash']);

            if (isset($_COOKIE['id'])) { //есть кука id
                //$alerts->add('всё ок! =)');

                $this->session_id = htmlspecialchars($_COOKIE['id']);
                //$this->user_group=0;//- это гость
                //$userdata = $mysqli->query("SELECT *, id FROM sessions WHERE hash = '".$_COOKIE['hash']."' LIMIT 1");
                $userdata = $db->query("SELECT *, id FROM sessions WHERE hash = '".$_COOKIE['hash']."' LIMIT 1");
                $userdata = $userdata->fetch_assoc();
                $insert = false;
                $update = false;
                $updatezer = false;
                if ($userdata) { //hash найден
                    if ($userdata['remember']=='1') {//время не важно
                        if (($userdata['hash']==$this->hash) and ($userdata['ip']==$this->ip) and ($userdata['id']==$this->session_id) and ($userdata['user_agent']==$this->agent)) {
                            $this->user_group=$userdata['user_group'];
                            $this->id=$userdata['user_id'];
                            $update = true;
                            $this->is_auth = true;
                        } else {
                            $updatezer = true;

                        }
                    } else { //надо проверить время
                        //$alerts->add($userdata['datetime']);
                        $datetime1 = DateTime::createFromFormat(DATETIMEFORMAT, $userdata['datetime']);
                        $datetime2 = new DateTime();
                        $interval = $datetime1->diff($datetime2);
                        //$alerts->add('$interval='.$interval->format('%y-%m-%d %h:%i:%s'),'warning',$interval);
                        if (($interval->y<1)&&($interval->m<1)&&($interval->days<2)) {
                            if (($userdata['hash']==$this->hash) and ($userdata['ip']==$this->ip) and ($userdata['id']==$this->session_id) and ($userdata['user_agent']==$this->agent)) {
                                $this->user_group=$userdata['user_group'];
                                $this->id=$userdata['user_id'];
                                $update = true;
                                $this->is_auth = true;
                            } else {
                                $updatezer = true;
                            }
                        } else {
                            $updatezer = true;
                        }
                    }
                } else { //hash не найден в БД
                    $insert = true;
                }
                if ($insert==true) { //хеш не найден, новая сессия
                    $this->hash = md5(generateCode(10));
                    //$mysqli->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."'");
                    $db->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."'");
                    //ставим куку
                    setcookie("hash", $this->hash, time()+60*60*24*30,'/');
                    setcookie("id", '', time()+60*60*24*30,'/');
                } else {
                    if ($updatezer==true) { //есть и хеш и id, но авторизацию не прошёл - понизить до гостя
                        $oldhash = $this->hash;
                        $this->hash = md5(generateCode(10));
                        //$mysqli->query("UPDATE sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."' WHERE hash='".$oldhash."'");
                        $db->query("UPDATE sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."' WHERE hash='".$oldhash."'");
                        setcookie("hash", $this->hash, time()+60*60*24*30,'/');
                        setcookie("id", '', time()+60*60*24*30,'/');
                    } else {
                        if ($update==true) {//всё ок, вытаскиваем необходимые данные и продолжаем сессию
                            //$userdata = $mysqli->query("SELECT user_login FROM users WHERE id = '".$this->id."' LIMIT 1");
                            $userdata = $db->query("SELECT user_login FROM users WHERE id = '".$this->id."' LIMIT 1");
                            $userdata = $userdata->fetch_assoc();

                            if ($userdata) {
                                $this->login=$userdata['user_login'];
                                //$mysqli->query("UPDATE sessions SET datetime=NOW() WHERE id='".$this->session_id."'");
                                $db->query("UPDATE sessions SET datetime=NOW() WHERE id='".$this->session_id."'");
                                setcookie("id", $this->session_id, time()+60*60*24*30,'/');
                                setcookie("hash", $this->hash, time()+60*60*24*30,'/');
                            } else {
                                $alerts->add('ERROR 1','danger');
                                setcookie("id", '', time()+60*60*24*30,'/');
                                setcookie("hash", '', time()+60*60*24*30,'/');
                            }


                        }
                    }
                }
            } else { //нет куки id

                //$userdata = $mysqli->query("SELECT *, id FROM sessions WHERE hash = '".$this->hash."' LIMIT 1");
                $userdata = $db->query("SELECT *, id FROM sessions WHERE hash = '".$this->hash."' LIMIT 1");
                $userdata = $userdata->fetch_assoc();

                //print_r($userdata);
                if ($userdata) { //найден
                    //$mysqli->query("UPDATE sessions SET datetime=NOW(), user_id='0', user_group='0', user_agent='".$this->agent."' WHERE hash='".$this->hash."'");
                    $db->query("UPDATE sessions SET datetime=NOW(), user_id='0', user_group='0', user_agent='".$this->agent."' WHERE hash='".$this->hash."'");
                    setcookie("hash", $this->hash, time()+60*60*24*30,'/');
                    setcookie("id", "", time()+60*60*24*30,'/');
                } else { //такой хеш не найден. Генерируем новый
                    $this->hash = md5(generateCode(10));
                    //$mysqli->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."'");
                    $db->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."'");
                    //ставим куку
                    setcookie("hash", $this->hash, time()+60*60*24*30,'/');
                    setcookie("id", "", time()+60*60*24*30,'/');
                }



            }
        } else { //нет куки хеша

            //записывае гостя в БД
            $this->hash = md5(generateCode(10));
            //$mysqli->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->agent."', lang='".$this->lang."'");
            $db->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->agent."', lang='".$this->lang."'");
            //ставим куку
            setcookie("hash", $this->hash, time()+60*60*24*30,'/');
            setcookie("id", "", time()+60*60*24*30,'/');
        }

        /*
                if (isset($_COOKIE['hash'])) {//есть кука хеша
                    if (isset($_COOKIE['id'])) { //есть кука id

                        /*
                        $userdata = $mysqli->query("SELECT *, ip FROM sessions WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");

                        $userdata = $userdata->fetch_assoc();

                        if(($userdata['hash'] !== htmlspecialchars($_COOKIE['hash'])) or ($userdata['id'] !== htmlspecialchars($_COOKIE['id']))
                            or (($userdata['ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['ip'] !== "0")))
                        {
                            if ($userdata['remember']=='1') {//время не имеет значения
                                $this->user_group=$userdata['usergroup'];
                            } else {//надо сравнить время
                                $alerts->add('$userdata[datetime]='.$userdata['datetime']);
                            }
                            //return false;
                        } else {
                            $this->user_group=0;//не авторизован
                            //return true;
                        }
                    } else { //нет куки id

                        $userdata = $mysqli->query("SELECT * FROM sessions WHERE hash = '".htmlspecialchars($_COOKIE['hash'])."' LIMIT 1");
                        $userdata = $userdata->fetch_assoc();

                        if () { //проверка что такой хеш найден в базе
                            if (($userdata['ip']==$_SERVER['REMOTE_ADDR']) and ($userdata['user_agent']==$this->user_agent)) {
                                //этот тот же чувак

                                $this->user_group=0;//- это гость
                                //обновляем БД
                                $this->hash = md5(generateCode(10));
                                //$mysqli->query("UPDATE sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', remote_addr='".$this->remote_addr."', lang='".$this->lang."'");
                                //обновляем куку
                                setcookie("hash", $this->hash, time()+60*60*24*30);

                            } else { //что то не совпадает

                            }
                        } else { //не найден в базе - он у нас в первый раз

                        }




                    }


                        //проверяем что пользователь авторизован

                    $userdata = $mysqli->query("SELECT * FROM sessions WHERE hash = '".htmlspecialchars($_COOKIE['hash'])."' LIMIT 1");

                    $userdata = $userdata->fetch_assoc();

                    if(($userdata['user_hash'] !== htmlspecialchars($_COOKIE['hash'])) or ($userdata['user_id'] !== htmlspecialchars($_COOKIE['id']))
                        or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['user_ip'] !== "0")))
                    {
                        return false;
                    } else {
                        return true;
                    }


                } else {//нет куки хеша
                    $this->user_group=0;//- это гость
                    //записывае гостя в БД
                    $this->hash = md5(generateCode(10));
                    $mysqli->query("INSERT INTO sessions SET user_id='0', user_group='0', hash='".$this->hash."', ip='".$this->ip."', user_agent='".$this->user_agent."', lang='".$this->lang."'");
                    //ставим куку
                    setcookie("hash", $this->hash, time()+60*60*24*30);
                }


        */

        if ($config->offline) {
            foreach ($config->offline_ip as $ip){
                if ($this->ip==$ip) $config->offline = false;//выключаем офлайн, если IP совпадает
            }
        }
        if ($this->user_group>1) $this->is_auth = true;

    }



    private function load( $file_and_array_name ) {
        /*
        * Загружает массивы из папки с массивами
        */
        $Load = require_once(YRNPATH_BASE. '/libraries/'.$file_and_array_name.'.php');
        $this->$file_and_array_name = (!count($Load))? array() : $Load;
    }

    private function set_lang() {
        //global $tmpstr;
        $this->lang_full = htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        if ($this->lang_full) {
            if (stripos($this->lang_full,',')===false) {//запятых нет
                if (stripos($this->lang_full,';')===false) {//запятых нет
                    $this->languages[] = $this->lang_full;//добавляем полностью строку
                } else {//точки с запятыми есть
                    $tmpstr = explode(";",$this->lang_full);//разбиваем на точки с запятыми
                    foreach ($tmpstr as $val) {
                        if (stripos($val,'=')===false) {//нету знака равно //если есть знак равно, это скорее всего 'q=0.8'
                            $this->languages[] = $val;//добавляем значение языка в массив
                        }
                    }
                }
            } else {//запятые есть
                $tmpstr = explode(",",$this->lang_full);//разбиваем на запятые
                foreach ($tmpstr as $val) {//перебираем значения
                    if (stripos($val,';')===false) {//точки с запятой нет
                        if (stripos($val,'=')===false) {//нету знака равно
                            $this->languages[] = $val;//добавляем значение языка в массив
                        }
                    } else {//точки с запятой есть
                        $tmpstr2 = explode(";",$val);//разбиваем на точки с запятыми
                        foreach ($tmpstr2 as $val2) {
                            if (stripos($val2,'=')===false) {//нету знака равно
                                $this->languages[] = $val2;//добавляем значение языка в массив
                            }
                        }
                    }
                }
            }
        } else {
            return false;
        }
        return true;
    }

    private function set_ip() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        return True;
    }

    private function set_browser() {
        if (is_array($this->browsers) and count($this->browsers) > 0) {
            foreach ($this->browsers as $key => $val) {
                if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match)) {
                    $this->is_browser = TRUE;
                    $this->version = $match[1];
                    $this->browser = $val;
                    $this->browser_full_name = $match[0];
                    return True;
                }
            }
        }
        return False;
    }

    private function set_operating_system() {
        if (is_array($this->operating_systems) AND count($this->operating_systems) > 0) {
            foreach ($this->operating_systems as $key => $val) {
                if (preg_match("|".preg_quote($key).".*?([a-zA-Z]?[0-9\.]+)|i", $this->agent, $match)) {
                    $this->operating_system = $val;
                    $this->os_version = $match[1];
                    return True;
                }
            }
        }
        $this->operating_system = 'Unknown';
    }

    private function set_robot() {
        if (is_array($this->robots) AND count($this->robots) > 0) {
            foreach ($this->robots as $key => $val) {
                if (preg_match("|".preg_quote($key)."|i", $this->agent)) {
                    $this->is_robot = TRUE;
                    $this->robot = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    private function set_mobile() {
        if (is_array($this->mobiles) AND count($this->mobiles) > 0) {
            foreach ($this->mobiles as $key => $val) {
                if (FALSE !== (strpos(strtolower($this->agent), $key))) {
                    $this->is_mobile = TRUE;
                    $this->mobile = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    #проверка прав пользователя
    public function a($right) {
        return true;
    }

    #проверка по гурппе пользователя
    public function g($group) {
        if ($this->user_group>=$group) {
            return true;
        } else {
            return false;
        }
    }
    public function check($component,$task = '',$action='') { //TODO: сделать что бы значение авторизации и группа пользователей запоминались
        global $db,$deb;
        $tmp = $component.'_'.$task.'_'.$action;
        $data = $db->query("SELECT * FROM users_access WHERE name='".$tmp."' LIMIT 1");
        $data = $data->fetch_array(MYSQLI_ASSOC);
        if ($data) {
            //$deb->ads('$data');

            $value = $data['value'];
            $values = explode(",", $value);
            $find = false;
            foreach ($values as $val) {
                $vall = explode(':',$val);
                if ($vall[0]==$this->user_group) {
                    $find=true;
                    $this->access[$tmp] = $vall[1];
                }//id if ==user_group
            } // end foreach $values

            if ($find) {
                if ($this->access[$tmp]=="2") {
                    return true;
                }
            } else { //if $find
                return false;
            } //if $find
        } else { //if $data
            //$deb->ads($tmp);
            return false;
        }//if $data
        /*
        if ($this->user_group==0) {
            return false;
        }
        if ($this->user_group==2) {
            return true;
        }*/
    }//function check

}// class usr

$user = new usr;