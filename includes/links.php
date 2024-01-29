<?php
/**
 * Created by
 * User: Roman
 * Date: 13.01.15
 * Time: 10:20
 * To more info see: http://ykushev.ru/
 */

defined('_YRNEXEC') or die;

class syslnk {
    public $url = '';
    public $countargs = 0;
    //public $args = array();
    public $active = false;

    public function __construct($url,$countargs=0) {
        $this->url=$url;
        $this->countargs=$countargs;
    }
}

class systemlinks {

    private $l = array();
    private $active = '';

    private function a($name,$urll,$countargs=0){//add
        global $app,$url,$deb;

        $this->l[$name]= new syslnk($urll,$countargs);

        if ($countargs>0) {

            $tmpint = strpos($urll,'%s');//разбиваем до первого аргумента и после
            if ($tmpint!==false) {//есть нижняя черта
                $tmpmass = explode('%s',$urll);//разбиваем
                if (strpos($url->full,$tmpmass[0])===0) {//если начало ссылки совпадает

                    //$deb->a($name);
                    $this->active = $name;
                    $tmpint = strrpos($name,'_');
                    if ($tmpint!==false) {//есть нижняя черта
                       $tmpmass = explode('_',$name);//разбиваем
                       $app->com = $tmpmass[0];
                       $app->task = $tmpmass[1];
                    }

                    //abc%sdef%s.html//образец

                    //$urlcurr = $url->full; //abc123def456.html//текущий урл
                    $urlcurr = substr($url->full,0,-5); //abc123def456.html//текущий урл
                    $urlobr = substr($urll,0,-5); //abc%sdef%s.html//текущий урл
                    $tmpint = strpos($urlobr,'%s');
                    while($tmpint!==FALSE) {
                       $tmpmass = explode('%s',$urlobr);//разбиваем строку образец
                       $tmpint3 = strpos($urlcurr,$tmpmass[1]);
                       if ($tmpint3>0) {
                           $param = substr($urlcurr,strlen($tmpmass[0]),strpos($urlcurr,$tmpmass[1]));//123def456.html//текущий урл
                       } else {
                           $param = substr($urlcurr,strlen($tmpmass[0]));//123def456.html//текущий урл
                       }
                       $app->params[]=$param;//сохраняем параметр
                       $tmpint2 = strpos($urlobr,'%s') + 2; //находим
                       $urlobr = substr($urlobr,$tmpint2);//def456.html//текущий урл
                       $tmpint = strpos($urlobr,'%s');//ищем следующий
                   }
               }//начало ссылки не сопадает

            } //не добавит ссылку если в ней нет на самом деле аргументов

            /**/
        } else {//если аргументов нет, то всё просто



            if ($url->full===$urll) {
                $this->active = $name;
                $tmpint = strrpos($name,'_');
                if ($tmpint!==false) {//есть нижняя черта
                    $tmpmass = explode('_',$name);//разбиваем
                    $app->com = $tmpmass[0];
                    $app->task = $tmpmass[1];
                }
            }//есть адрес совпадает 100% запоминаем имя активной
        }



    }

    public function g($type,$args = array()) {//get
        global $deb;
        if ($this->l[$type]) { //если такая ссылка вообще есть

            if (count($args)>0) {//если аргументы есть
                $tmpstr = $this->l[$type]->url;//временно сохраняем
                $tmpstr = vsprintf($tmpstr, $args);//производим замену по аргументам
                return $tmpstr;
            } else {
                return $this->l[$type]->url;
            }
        } else { //если ссылки нет - неудача
            return '';
        }
    }

    public function isactive($type){
        if ($type==$this->active){
            return 'active';
        } else {
            return '';
        }
    }

    public function getpath() {
        global $app;
        if ($app->com=='') {//если ни одна из ссылок меню не активна
            if ($this->active!=='') {//если одна из системных ссылок активна
                $tmpint = strrpos($this->active,'_'); //ищем нижнюю черту в названии
                if ($tmpint!==false) {//есть нижняя черта
                    $tmpmass = explode('_',$this->active);//разбиваем
                    $app->com = $tmpmass[0];
                    $app->task = $tmpmass[1];
                } else { //нет нижней черты
                    $app->com = $this->active; //наше название и есть компонент
                }
            }
        }
    }

    public function init() {
        $this->a('main','');

        $this->a('users_main','users.html');
        $this->a('users_add','users/add.html');
        $this->a('users_verified','users/verified.html');
        $this->a('users_login','login.html');
        $this->a('users_register','register.html');
        $this->a('users_logout','logout.html');
        $this->a('users_settings','settings.html');
        $this->a('users_profile','users/profile/%s.html',1);

        $this->a('pages_main','pages.html');
        $this->a('pages_add','pages/add.html');
        $this->a('pages_edit','pages/edit/%s.html',1);
        $this->a('pages_page','pages/%s.html',1);

        $this->a('diary_main','diary.html');
        $this->a('diary_add','diary/add.html');
        $this->a('diary_edit','diary/edit/%s.html',1);
        $this->a('diary_post','diary/%s/%s_%s.html',3); //diary/!admin!/!150231!_!1538!.html 15.02.31 15:38

        $this->a('logs_main','logs.html');
        $this->a('logs_add','logs/add.html');
    }

}
$slnk = new systemlinks;

//$syslnk = array();
