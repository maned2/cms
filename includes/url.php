<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Роман
 * Date: 18.02.15
 * Time: 1:16
 * To change this template use File | Settings | File Templates.
 */
 
 defined('_YRNEXEC') or die;

class urll {
    public $full = '';
    public $path = array();// users,profiles,1
    public $names = array();

    public function init() {
        $uri = htmlspecialchars($_SERVER['REQUEST_URI']);
        if (strrpos($uri,'?')!==false) {//отсекам GET параметры после знака вопроса
            $tmpstr =  explode('?',$uri);
            $uri = $tmpstr[0];
        }


        //while (strpos($uri,'/'))
        $possl = strpos($uri,'/');
        if ($possl==0) { //есть / и он первый
            //$alerts->add('$possl===0');
            $uri = substr($uri,1,strlen($uri));//отрезаем его
        }
        $this->full = $uri;
        $poshtml = strrpos($uri,'.html');

        if ($poshtml>0) {//есть .html
            //$alerts->add('$poshtml>0');
            $uri = substr($uri,0,-5); //отрезаем его
        }



        $possl = strrpos($uri,'/');
        if ($possl!==false) {//ещё есть слеши
            $urim = explode('/',$uri);//разбиваем
            $this->path = $urim;
        } else {//больше нет слешей
            $this->path[0] = $uri; //первый и есть наша цель
        }
    }
}

$url = new urll();