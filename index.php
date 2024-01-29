<?php
/**
 * Created by Yakushev Roman.
 * User: Roman
 * Date: 18.12.14
 * Time: 14:41
 * To more info see: http://ykushev.ru/
 */

define('_YRNEXEC', 1);
$time_start = microtime(true);//замеряем время
define('YRNPATH_BASE', __DIR__);
define('YRNHTTP_HOST', $_SERVER['HTTP_HOST']);
define('YRNHTTP_HOSTFULL', 'http://'.$_SERVER['HTTP_HOST'].'/');

require_once(YRNPATH_BASE.'/includes/functions.php');

$url->init();//разбиваем запрос пользователя
$slnk->init();//объявляем системные ссылки
$tmpl->init();//выясняем какой шаблон будем загружать
$menus->init();//загружаем список меню из БД
$user->init();//определяем пользователя
$lang->init();//определяем язык, загружаем из файлов
$alerts->loadget();//загружаем сообщения из адресной строки

switch ($app->com) {
    case "users":
        $tmpl->component='users';
       // $alerts->add($urim[1]);
        require_once(YRNPATH_BASE.'/components/users.php');
        break;
    default:
        if ($url->full=='') {
            $tmpl->component='main';
        } else {
            $tmpl->error=404;
        }
        //echo 'тут совсем ничего нет';
}

//$alerts->add($user->user_group);

$tmpl->output();//запускаем вывод шаблона



//$tmpl->echotmp();

exec_end();//Завершающая функция
$db->close();


//mysql_end($mysqli);
