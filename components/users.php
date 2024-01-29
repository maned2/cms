<?php
/**
 * Created by
 * User: Маром
 * Date: 10.01.15
 * Time: 11:42
 */

defined('_YRNEXEC') or die;
class oneuser {
    public $login = '';
    public $name = '';
    public $secname = '';
    public $avatar = '';
    public $link = '';
    public $status = '';

    public function __construct($login,$name,$secname,$avatar,$link,$status){
        $this->login = $login;
        $this->name = $name;
        $this->secname = $secname;
        $this->avatar = $avatar;
        $this->link = $link;
        $this->status = $status;
    }
}
class users1 {
    public $mass = array();

    public function getlink($id,$task){
        global $slnk;
        if ($this->mass[$id]->login!=='') {
            return $this->mass[$id]->login.'.html';
        } else {
            $slnk->g('users_profile',$id);
        }
    }
}
$users = new users1;


    switch ($app->task) {
        case 'main':
            if ($user->check('users','main','read')) {
                $tmpl->task='main';
                $basedata = $db->query("SELECT * FROM users_profiles LEFT JOIN users USING(id) ORDER BY users_profiles.rating DESC LIMIT 20");//TODO: добавить в конфиг лимит
                if ($basedata->num_rows>0) { //hash найден
                    while ($ouser = $basedata->fetch_assoc()) { //перебираем ссылки
                        $link = $slnk->g('users_profile',$ouser['id']);
                        $users->mass[$ouser['id']] = new oneuser($ouser['user_login'],$ouser['name'],$ouser['secname'],$ouser['avatar'],$link,$ouser['status']);
                        $tmpl->ob[] = array(
                            'login' => $ouser['user_login'],
                            'name' => $ouser['name'],
                            'secname' => $ouser['secname'],
                            'avatar' => $ouser['avatar'],
                            'link' => $link,
                            'status' => $ouser['status'],
                        );
                    }
                }
            }
            break;
        case 'register':
            $tmpl->task='register';
            //$alerts->add(date(DATETIMEFORMAT,time()));
            if(isset($_POST['submit'])) {

                # проверям логин
                if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])) {
                    //$err[] = "Логин может состоять только из букв английского алфавита и цифр";
                    $alerts->add($lang->g('USERS_ERROR_LOGINWRONG'),'danger');
                }
                if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) {
                    //$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
                    $alerts->add($lang->g('USERS_ERROR_LOGINLENGTH'),'danger');
                }

                # проверям е-маил
                $email = htmlspecialchars($_POST['email']);
                if(!preg_match("/@{1}/",$email)) {
                    //$err[] = "Логин может состоять только из букв английского алфавита и цифр";
                    $alerts->add($lang->g('USERS_ERROR_EMAILWRONG'),'danger');
                }

                $login = $mysqli->real_escape_string($_POST['login']);
                # проверяем, не сущестует ли пользователя с таким именем
                $query = $mysqli->query("SELECT COUNT(user_id) FROM users WHERE user_login='".$login."'");
                $rezcount = $query->fetch_row();
                if($rezcount[0] > 0) {
                    //$err[] = "Пользователь с таким логином уже существует в базе данных";
                    $alerts->add($lang->g('USERS_ERROR_LOGINEXISTS'),'danger');
                }

                # Если нет ошибок, то добавляем в БД нового пользователя
                if($alerts->dangers == 0) {

                    $emailhash = md5(generateCode(10));
                    # Убераем лишние пробелы и делаем двойное шифрование
                    $password = md5(md5(trim($_POST['password'])));
                    $log->add('register new user at '.date(DATETIMEFORMAT),$user->ip);
                    $tmptime = new DateTime();
                    $tmpstr = "INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_group='1', email='".$email."', email_hash='".$emailhash."', email_send='".$tmptime->format(DATETIMEFORMAT)."'";
                    $log->add($tmpstr,$user->ip);
                    $db->query($tmpstr);

                    $tmpsubject = $lang->g('USERS_EMAIL_VERIFIED_SUBJECT').' '.$config->sitename;
                    $tmplink = YRNHTTP_HOSTFULL.$syslnk->m['users']['verified'].'?hash='.$emailhash;
                    $tmpmsg = '
<p>'.$lang->g('USERS_EMAIL_VERIFIED_PRELINK_TEXT').'</p>
<p><a href="'.$tmplink.'" class="btn btn-success btn-lg" role="button">'.$lang->g('USERS_EMAIL_VERIFIED_LINK').'</a></p>
<p>'.$lang->g('USERS_EMAIL_VERIFIED_PRECODE_TEXT').'</p>
<pre>'.$tmplink.'</pre>
                    ';

                    send_mail($config->email_site,$email,$tmpsubject,$tmpmsg,true);// send html email
                    $tmpl->redirect = $syslnk->m['users']['login'].'?from=register';
                    //header("Location: login.php"); exit();
                } //no errors
            }//post submit
            break;
        case 'login':
            $tmpl->task='login';
            if(isset($_POST['submit'])) {



                # Вытаскиваем из БД запись, у которой логин равняеться введенному
                $data = $db->query("SELECT id, user_password, user_group FROM users WHERE user_login='".$mysqli->real_escape_string($_POST['login'])."' LIMIT 1");
                $data = $data->fetch_assoc();

                # Соавниваем пароли
                if($data['user_password'] === md5(md5($_POST['password']))){

                    if ($data['user_group']=='1') {
                        $alerts->add($lang->g('USERS_LOGIN_NOVERIFIED'),'danger');
                    } else {

                        if (isset($_POST['remember'])) {
                            $remember = "remember='1'";
                        } else {
                            $remember = "remember='0'";
                        }
                        $sess = $db->query("SELECT id FROM sessions WHERE hash='".$user->hash."' LIMIT 1");
                        $sess = $sess->fetch_assoc();

                        # Генерируем случайное число и шифруем его
                        $hash = md5(generateCode(10));
                        //$insip = ", ip='".$_SERVER['REMOTE_ADDR']."'";

                        # Записываем в БД новый хеш авторизации и IP
                        $db->query("UPDATE sessions SET hash='".$hash."', user_group='".$data['user_group']."', user_id='".$data['id']."', ".$remember." WHERE id='".$sess['id']."'");

                        # Ставим куки
                        setcookie("id", $sess['id'], time()+60*60*24*30,'/');
                        setcookie("hash", $hash, time()+60*60*24*30,'/');

                        # Переадресовываем браузер на страницу проверки нашего скрипта
                        $tmpl->redirect = '/';
                        //header("Location: check.php"); exit();
                    }
                } else {

                    setcookie("id", "", time()+60*60*24*30,'/');
                    //setcookie("hash", "", time()+60*60*24*30,'/');
                    //$err[] = $lang->g('USERS_ERROR_LOGPASS');
                    $alerts->add($lang->g('USERS_ERROR_LOGPASS'),'danger');
                    //= $lang->g('USERS_ERROR_LOGPASS');
                    //$err[] = "Вы ввели неправильный логин/пароль";
                    $log->add('USERS_ERROR_LOGPASS',$user->ip);
                }

            }//isset post submit
            if (isset($_GET['from'])) {
                $from = htmlspecialchars($_GET['from']);
                switch ($from) {
                    case 'register':
                        $alerts->add($lang->g('USERS_REGISTER_SUCCESS_MSG'),'success');
                }
            }

            break;
        case 'logout':
            $db->query("UPDATE sessions SET user_id='0', user_group='0' WHERE id='".$user->id."'");
            setcookie("id", "", time()+60*60*24*30,'/');
            setcookie("hash", "", time()+60*60*24*30,'/');
            $tmpl->redirect = '/';
            break;
        case 'verified':
            if (isset($_GET['hash'])) {
                $hash = $db->real_escape_string(htmlspecialchars($_GET['hash']));
                $data = $db->query("SELECT email_hash, email_send FROM users WHERE email_hash='".$hash."' LIMIT 1");
                //$alerts->add('$data=','warning',$data);
                if ($data->num_rows>0) {
                    $data = $data->fetch_assoc();
                    //$alerts->add($data['email_send'],'warning');
                    //TODO: вывести сообщение если пользователь уже подтверждал свой е-маил
                    $email_send = "";
                    $email_send = $data['email_send'];
                    //$alerts->add('$email_send='.$email_send,'info',$email_send);
                    $datetime1 = DateTime::createFromFormat(DATETIMEFORMAT, $email_send);
                    $datetime2 = new DateTime();
                    //$alerts->add('$datetime1='.$datetime1->format(DATETIMEFORMAT),'info',$datetime1);
                    //$alerts->add('$datetime2='.$datetime2->format(DATETIMEFORMAT),'info',$datetime2);
                    $interval = $datetime1->diff($datetime2);
                    $alerts->add('$interval='.$interval->format('%y-%m-%d %h:%i:%s'),'warning',$interval);
                    if (($interval->y<1)&&($interval->m<1)&&($interval->days<2)) {
                        $db->query("UPDATE users SET user_group='2' WHERE email_hash='".$hash."'");
                        //$alerts->add($lang->g('USERS_EMAIL_VERIFIED_SUCCESS'),'success');
                        $tmpl->redirect = $syslnk->m['users']['login'].'?m=USERS_EMAIL_VERIFIED_SUCCESS';
                    } else {
                        $alerts->add($lang->g('USERS_EMAIL_VERIFIED_FAIL'),'danger');
                    }
                } else {
                    $alerts->add($lang->g('USERS_EMAIL_VERIFIED_FAIL'),'danger');//TODO: продумать вариант неудачи с подтверждением регистрации
                }
            } else {
                $tmpl->redirect = '/';
            }
            break;
        case 'profile':
            //$deb->a($app->task);
            //$deb->a($app->params);


            $basedata = $db->query("SELECT users_profiles.*, users.* FROM users_profiles,users WHERE users_profiles.id = ".$app->params[0]);//TODO: добавить в конфиг
            $basedata2 = $db->query("SELECT * FROM sessions WHERE user_id = ".$app->params[0]." ORDER BY datetime DESC LIMIT 1");//TODO: добавить в конфиг
            if ($basedata->num_rows>0) { //данные найдены
                $tmpl->task='profile';
                $basedata = $basedata->fetch_assoc();
                $basedata2 = $basedata2->fetch_assoc();

                $link = $slnk->g('users_profile',$app->params[0]);
                $nowdate = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

                $register = DateTime::createFromFormat('Y-m-d', $basedata['register']);
                //$withus = $now - $register;
                $withus = $nowdate->diff($register);
                $lastonline = DateTime::createFromFormat('Y-m-d H:i:s', $basedata2['datetime']);
                $now = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $lastonlineint = $now->diff($lastonline);

                $timeonline = DateInterval::createFromDateString('5 min');//TODO: Вынести в конфиг
                //$timeonline->invert = 1;
                if (($lastonlineint->y<=0)&&($lastonlineint->m<=0)&&($lastonlineint->d<=0)&&($lastonlineint->h<=0)&&($lastonlineint->i<5)) {
                    $online = true;
                } else {
                    $online = false;
                }
                //$online = $timeonline->diff($lastonlineint);
                //$deb->a($lastonlineint->y<=0);
                //$deb->a($online);

                //$deb->a($lastonline);
                //$users->mass[$ouser['id']] = new oneuser($ouser['user_login'],$ouser['name'],$ouser['secname'],$ouser['avatar'],$link,$ouser['status']);
                $tmpl->ob[] = array(
                    'login' => $basedata['user_login'],
                    'name' => $basedata['name'],
                    'secname' => $basedata['secname'],
                    'avatar' => $basedata['avatar'],
                    'link' => $link,
                    'register' => $register,
                    'withus' => $withus,
                    'status' => $basedata['status'],
                    'lastonline' => $lastonline,
                    'online' => $online
                );
            } else {
                $tmpl->error=404;
            }//такой id не найден

            break;
    }//switch task