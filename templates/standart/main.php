<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Роман
 * Date: 26.01.15
 * Time: 0:57
 * To change this template use File | Settings | File Templates.
 */
 
 defined('_YRNEXEC') or die;



if ($user->is_auth==true) {
echo '<h1>Вы успешно авторизовались</h1>';
//echo 'вы успешно вошли';
} else {
echo '<h1>Тут ничего нет. Пока.</h1>';
/*$alerts->add($user->ip);
$alerts->add($user->version);
$alerts->add($user->browser);
$alerts->add($user->browser_full_name);
$alerts->add($user->operating_system);
$alerts->add($user->os_version);
$alerts->add($user->robot);
$alerts->add($user->mobile);
$alerts->add($_SERVER['HTTP_USER_AGENT']);
$alerts->add($_SERVER['HTTP_ACCEPT_LANGUAGE']);
$alerts->add($_SERVER['REMOTE_USER']);
$alerts->add($user->lang);*/
}
?>