<?php
/**
 * Created by Yakushev Roman.
 * User: Roman
 * Date: 26.01.15
 * Time: 12:53
 * To more info see: http://ykushev.ru/
 */
?>
<?php if ($user->is_auth) { //авторизован ?>
    <li class="dropdown">
        <a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_userprofile',[$user->id]);?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
            <?php echo 'Уведомления'?> <span class="badge">42</span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#"><?php echo $lang->g('USERS_MY_PROFILE');?></a></li>
            <li><a href="#"><?php echo $lang->g('USERS_MY_SETTINGS');?></a></li>
            <li class="divider"></li>
            <li><a href="#"><?php echo $lang->g('USERS_LOGOUT_LINK');?></a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_userprofile',[$user->id]);?>" class="dropdown-toggle ava" data-toggle="dropdown" role="button" aria-expanded="false">
            <?php if ($user->avatar=='') {
                echo '<img src="'.$this->tmpurl.'images/noavatar.jpg" class="img-circle"/>';
            }?>
            <?php echo $user->login;?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_profile',[$user->id]);?>" >
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $lang->g('USERS_MY_PROFILE');?></a></li>
            <li><a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_settings');?>">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo $lang->g('USERS_MY_SETTINGS');?></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_logout');?>">
                    <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> <?php echo $lang->g('USERS_LOGOUT_LINK');?></a></li>
        </ul>
    </li>

<?php } else { //не авторизован ?>
    <li class="<?php echo $slnk->isactive('users_register');?>"><a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_register');?>" ><?php echo $lang->g('USERS_REGISTER_LINK');?></a></li>
    <li class="<?php echo $slnk->isactive('users_login');?>"><a href="<?php echo YRNHTTP_HOSTFULL.$slnk->g('users_login');?>"><?php echo $lang->g('USERS_LOGIN_LINK');?></a></li>
<?php } ?>

