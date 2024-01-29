<?php
/**
 * Created by
 * User: Маром
 * Date: 10.01.15
 * Time: 11:47
 */

defined('_YRNEXEC') or die;

class templates {
    public $main = '';
    public $redirect = '';//куда редиректить
    public $template = '';//какой шаблон загружать
    public $tmpurl = '';//адрес шаблона
    public $tmpname = '';//адрес шаблона
    public $component = '';//какой компонент
    public $task = '';//какая задача
    public $menu = array();//динамическое меню
    public $ob = array();
    public $error = 0;

    public function init() {
        $this->tmpname = 'standart';//TODO: получить название шаблона из базы данных
        $this->tmpurl = YRNHTTP_HOSTFULL.'templates/'.$this->tmpname.'/';
    }

    public function module($name) {
        global $lang,$user,$slnk;
        $tmpstr = YRNPATH_BASE.'/templates/'.$this->tmpname.'/modules/'.$name.'.php';
        if (is_file($tmpstr)){
            include($tmpstr);
        }
    }

    public function debug($before,$after) {
        global $deb,$app,$time_start;
        $deb->g($before,$after);
        $time_end = microtime(true);//замеряем время
        $time = $time_end - $time_start;
        echo 'Время: '.$time;
    }

    public function menu($alias) {
        global $lang,$menus,$alerts;
        if ($menus->get($alias)) {
            $this->menu = $menus->m[$alias];//TODO: Изменить на $this->menu[$alias] и добавить проверку
            $tmpstr = YRNPATH_BASE.'/templates/'.$this->tmpname.'/modules/menu.php';
            if (is_file($tmpstr)){
                include($tmpstr);
            }
        }

    }

    public function alerts() {
        global $alerts,$lang;
        $tmpstr = YRNPATH_BASE.'/templates/'.$this->tmpname.'/alerts.php';
        if (is_file($tmpstr)){
            include($tmpstr);
        }
    }

    public function component() {
        global $alerts,$lang,$user,$deb;

        if ($this->component=='main') {
            $tmpstr = YRNPATH_BASE.'/templates/'.$this->tmpname.'/'.$this->component.'.php';
            if (is_file($tmpstr)){
                include($tmpstr);
            }
        } else {
            $tmpstr = YRNPATH_BASE.'/templates/'.$this->tmpname.'/components/'.$this->component.'/'.$this->task.'.php';
            if (is_file($tmpstr)){
                include($tmpstr);
            } else {
                $alerts->add($lang->g('ERROR_404'),'danger');
            }
        }
    }

    public function addMain($str) {
        $this->main .= $str;
    }

    public function output() { //начинаем вывод контента
        global $app;
        if ($this->redirect!='') {//если есть редирект -
            $app->end();//завершаем php
            //exec_end();
            header("Location: ".$this->redirect); //редиректим
            //$mysqli->close();
            //mysql_end($mysqli);
            exit();//выходим
        } else {
            global $config,$alerts, $mainmenu, $lang;
            if($config->offline) {//TODO: отдельная страница что сайт закрыт
                $alerts->add($lang->g('SITE_OFFLINE'),'danger');//TODO: отдельная страница что сайт закрыт
                $this->alerts();//вывод ошибок
            } else {//TODO: 404 страница
                include(YRNPATH_BASE.'/templates/'.$this->tmpname.'/index.php');
            }
        }
    }

    public function echotmp() {
        global $alerts, $lang;
        global $err;
        if ($this->redirect!='') {
            exec_end();//завершаем php
            header("Location: ".$this->redirect);
            //$mysqli->close();
            //mysql_end($mysqli);
            exit();
        } else {

            echo '<html><head>';
            echo '<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="'.YRNHTTP_HOSTFULL.'templates/css/bootstrap.min.css" rel="stylesheet">
    <link href="'.YRNHTTP_HOSTFULL.'templates/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="'.YRNHTTP_HOSTFULL.'templates/css/theme.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->';


            echo '</head><body><div class="container">';
            /*
             * if (count($err)) {
                echo '<div class="errors">';
                foreach ($err as $error) {
                    echo '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Ошибка:</strong> '.$error.'</div>';
                }
                echo '</div>';
            }
            */
            if (count($alerts->mass)) {
                echo '<div class="alerts">';
                foreach ($alerts->mass as $key => $value) {

                    //echo '$key = '.$key;
                    //echo '<br/>$value = '.$value;
                    echo '<div class="alert alert-'.$value['type'].' alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="glyphicon '.$value['icon'].'" aria-hidden="true"></span>
                    <strong>'.$lang->g('ALERTS_TYPE_'.$value['type']).':</strong> '.$value['msg'];
                    if ($value['var']){
                        echo ' = ';
                        print_r($value['var']);
                    }
                    echo '</div>';

                }
                echo '</div>';
            } //end foreach alerts

            echo $this->main;
            $tmpdatetime = new DateTime();
            echo '<pre>UTC+0 = '.$tmpdatetime->format(DATETIMEFORMAT).'</pre>';
            echo '<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="'.YRNHTTP_HOSTFULL.'templates/js/bootstrap.min.js"></script>
    <script src="'.YRNHTTP_HOSTFULL.'templates/js/theme.js"></script>
    ';
            echo '</body></html>';
        }
    }//function echotmp()
}// class templates

$tmpl = new templates;