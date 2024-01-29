<?php
/**
 * Created by
 * User: Маром
 * Date: 09.01.15
 * Time: 1:00
 */

class language {
    public $name = '';
    public $code = '';
    public $codes = array();

    public function __construct($codes,$name='') {
        $this->codes = $codes;
        $this->name = $name;
    }
}

defined('_YRNEXEC') or die;

class languages {
    public $languages = array();
    public $masslan = array();
    public $current = '';
    public $components = array('system','users','rights','links');
    public $result = array();
    public $defaultlanguage = 'en';//TODO: вынести в конфиг

    public function init() {
        $this->languages['ru'] = new language(array('ru','ru-RU'),'Русский');
        $this->languages['en'] = new language(array('en','en-US'),'English');

        if (!$this->setlang()) $this->current = $this->defaultlanguage;//если не удалось определить язык - ставим язык по умолчанию

        $this->load();
    }

    /*public function __construct() {

        $this->languages['ru'] = new language(array('ru','ru-RU'),'Русский');
        $this->languages['en'] = new language(array('en','en-US'),'English');

        if (!$this->setlang()) $this->current = $this->defaultlanguage;//если не удалось определить язык - ставим язык по умолчанию

        $this->load();

    }//public function __construct() {*/

    public function setlang() {
        global $user;
        foreach ($user->languages as $usrlang) {//перебираем языки пользователей
            foreach ($this->languages as $key=>$lnglang) {//перебираем языки сайта
                foreach ($lnglang->codes as $code) {//перебираем коды у языков сайта
                    //$alers->add($usrlang.'=='.$code);
                    if ($usrlang==$code) {//язык пользователя совпадает с кодом языка сайта
                        $this->current = $key;//текущий язык найден
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function load(){//загружаем переменные языка
        foreach ($this->components as $comp) {
            if (is_file(YRNPATH_BASE."/languages/".$this->current."/".$comp.".ini")) {
                $tmp = parse_ini_file(YRNPATH_BASE."/languages/".$this->current."/".$comp.".ini");
                if ($tmp!==FALSE) {
                    $this->result = array_merge($this->result,$tmp);
                }
            }
        }
    }

    public function g($const) {
        if ($this->result[$const]) {
            return $this->result[$const];
        } else {
            if ($this->result[strtoupper($const)]) {
                return $this->result[strtoupper($const)];
            } else {
                return $const;
            }
        }
    }

}//class languages


$lang = new languages;