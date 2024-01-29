<?php
/**
 * Created by
 * User: Роман
 * Date: 04.01.15
 * Time: 14:16
 */
defined('_YRNEXEC') or die;

class logging {
    public $logs = array();
    public $count = 0;

    public function add($str, $ip, $type=0, $user=0, $usergroup=0) {
        global $user;
        $this->logs[$this->count]['message'] = $str;
        $this->logs[$this->count]['ip'] = $ip;//TODO: Брать IP из класса users
        $this->logs[$this->count]['type'] = $type;
        $this->logs[$this->count]['user'] = $user;
        $this->logs[$this->count]['usergroup'] = $usergroup;
        $this->count++;
    }

    public function end(){
        global $mysqli;
        if(count($this->count)>0){
            foreach ($this->logs as $item) {
                $mysqli->query("INSERT INTO logs SET type='".$item['type']."', user='".$item['user']."', ip='".$item['ip']."', message='".$item['message']."', usergroup='".$item['usergroup']."'");
            }
        }
    }
}

$log = new logging;