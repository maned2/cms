<?php
/**
 * Created by
 * User: Роман
 * Date: 23.02.15
 * Time: 23:21
 */
 
 defined('_YRNEXEC') or die;

class debugg {
    public $on = false;
    private $vars = array();
    private $strings = array();

    public function a($var) {
        $this->vars[] = $var;
    }
    public function ads($str,$type = ''){
        if ($type) {
            $this->strings[$type][]=$str;
        } else {
            $this->strings['all'][]=$str;
        }
    }

    public function g($before,$after) {
        echo $before;
        foreach ($this->strings as$key=>$type) {
            echo $key.'<br/>';
            foreach ($type as $string) {
                echo $string.'<br/>';
            }
        }
        echo $after;
        foreach ($this->vars as $var) {
            echo $before;
            var_dump($var);
            echo $after;
        }
    }

}

$deb = new debugg;
$deb->on = true;//TODO: вынести в конфиг