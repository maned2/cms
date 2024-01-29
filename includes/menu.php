<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Роман
 * Date: 26.01.15
 * Time: 2:15
 * To change this template use File | Settings | File Templates.
 */
 
 defined('_YRNEXEC') or die;

class link {
    public $text = '';
    public $link = '';
    public $level = 0;//по умолчанию тот же уровень
    public $target = 0;
    public $active = false;

    //public $

    public function __construct($link,$text,$level,$target=null,$active=false){
        $this->text=$text;
        $this->link=$link;
        //$this->usergroups=$usergroups;
        $this->target=$target;
        $this->level=$level;
        $this->active=$active;
        //$alerts->add("active=",'danger',$active);
        //TODO: add other fields
    }

}//end class link


class menus {
    public $m = array();
    private $menus = array();
    private $loaded = array();

    public function init(){
        global $db,$mysqli,$debug;
        //$userdata = $mysqli->query("SELECT * FROM menu");//выбираем все меню TODO: кеширование
        $userdata = $db->query("SELECT * FROM menu");//выбираем все меню TODO: кеширование
        //$userdata = $userdata->fetch_assoc();
        if ($userdata) { //hash найден
            while ($menu = $userdata->fetch_assoc()) {
                $this->menus[$menu['name']]['id']=$menu['id'];
                $this->menus[$menu['name']]['user_group']=$menu['user_group'];
                $this->menus[$menu['name']]['published']=$menu['published'];
                $this->loaded[$menu['name']] = false;
                //$this->m[$menu['name']] = array();//TODO: может не нужно
            }
        }
    }

    public function get($alias) {//функция получения меню из БД по алиасу
        global $db,$app,$mysqli,$user,$alerts,$url,$lang;
        if ($user->g($this->menus[$alias]['user_group'])) { //доступ к меню есть
            if ($this->loaded[$alias]==false) { //ещё не загружено
                //$userdata = $mysqli->query("SELECT * FROM menu_links WHERE menu = '".$alias."' ORDER BY order");
                //$userdata = $mysqli->query("SELECT * FROM menu_links WHERE menu = '".$alias."' ORDER BY ord");
                $userdata = $db->query("SELECT * FROM menu_links WHERE menu = '".$alias."' ORDER BY ord");
                //$userdata = $userdata->fetch_assoc();
                if ($userdata) { //hash найден
                    $unaccess = 0;
                    $prevld = true;
                    while ($link = $userdata->fetch_assoc()) { //перебираем ссылки
                        if (($user->g($link['user_group']))&&($link['published']=='1')) { //есть доступ к ссылке
                            //$parentloaded = $link['level'];
                            if ($prevld==true) { //предыдущая ссыллка была загружена
                                $level = (int)$link['level'];
                                $active = false;//по умолчанию ссылка не активна
                                if ($url->path[$level]==$link['alias']) {//алиас совпадает с url
                                    $active = true;
                                    if ($link['link']==$url->full) {//полностью совпадает адресная строка и путь ссылки
                                        if (strrpos($link['type'],'_')) { //ищем нижнюю черту в типе ссылки
                                            $tmpstr = explode('_',$link['type']);
                                            $app->com = $tmpstr[0];
                                            $app->task = $tmpstr[1];
                                        } else {//нижнего подчёркивания нет - ставим только компонент
                                            $app->com = $link['type'];
                                        }
                                    } //если путь полностью не совпадает - одна из подссылок должна быть активна
                                }// если алиас не совпадает - ссылка не активная

                                $this->m[$alias][] = new link(YRNHTTP_HOSTFULL.$link['link'],$link['name'],$level,$link['target'],$active);

                            } else { //предыдудущая ссылка не была учтена
                                if ($unaccess<=$link['level']) {//как только уровень на котором доступ был запрещён становится таким же, или меньше,
                                    $unaccess = $link['level'];
                                }
                            }

                            //$oldlvl = $link['level'];
                        } else { //нет доступа к ссылке
                            $unaccess = $link['level'];//запоминаем на каком уровне не загрузилась
                            $prevld=false;
                        }
                    }//end while
                    return true;
                } else { //не найдено
                    $alerts->add('ERROR_2','danger');
                    //$alerts->add($alias,'danger');
                    return false;
                }
            } else {//уже загружалось
                return true;
            }
        } else {//доступа к меню нет
            $alerts->add('ERROR_1','danger');
            return false;//неудачно
        }

    }
}

$menus = new menus;
