<?php
/**
 * Created by
 * User: Маром
 * Date: 09.01.15
 * Time: 13:55
 */

defined('_YRNEXEC') or die;

class yrnalerts {
    public $mass = array();
    public $count = 0;
    public $dangers = 0;

    public function __construct() {

    }

    public function add($msg, $type="info", $var="", $admin=false) {
        $this->mass[$this->count]['type'] = $type;
        $this->mass[$this->count]['msg'] = $msg;
        /*if ($var) {
            $this->mass[$this->count]['var'] = $var;
        }*/
        $this->mass[$this->count]['var'] = $var;
        $this->mass[$this->count]['adm']=$admin;

        switch ($type){
            case 'success':
                $this->mass[$this->count]['type'] = 'success';
                $this->mass[$this->count]['icon']= 'glyphicon-ok';
                break;
            case 'warning':
                $this->mass[$this->count]['type'] = 'warning';
                $this->mass[$this->count]['icon']= 'glyphicon-exclamation-sign';
                break;
            case 'danger':
                $this->mass[$this->count]['type'] = 'danger';
                $this->mass[$this->count]['icon']= 'glyphicon-remove';
                $this->dangers++;
                break;
            default:
                $this->mass[$this->count]['type'] = 'info';
                $this->mass[$this->count]['icon']= 'glyphicon-info-sign';
                break;
        }
        $this->count++;
    }//function add
    public function loadget() {
        if (isset($_GET['m'])) {//есть сообщение
            global $tmp, $lang;
            $tmp = htmlspecialchars($_GET['m']);
            $tmp2 = $lang->g($tmp);
            $tmp3 = explode("_",$tmp);
            $tmp = strtolower($tmp3[count($tmp3)-1]);
            $this->add($tmp2,$tmp);
            //$this->add($tmp,'danger',$tmp);
        }
    }
}

$alerts = new yrnalerts;