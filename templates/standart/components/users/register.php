<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Роман
 * Date: 24.01.15
 * Time: 23:00
 * To change this template use File | Settings | File Templates.
 */
defined('_YRNEXEC') or die;
?>

<form method="POST" class="form-signin">
    <h2 class="form-signin-heading"><?php echo $lang->g('USERS_REGISTER_PAGE_HEADING'); ?></h2>

    <label for="inputLogin" class="sr-only"><?php echo $lang->g('USERS_LOGIN'); ?></label>
    <div class="input-group">
        <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
        <input id="inputLogin" name="login" type="text" class="form-control" placeholder="<?php echo $lang->g('USERS_LOGIN'); ?>" required="" data-toggle="popover" data-placement="top" title="<?php echo $lang->g('USERS_LOGIN'); ?>" data-content="<?php echo $lang->g('USERS_LOGIN_DESCR'); ?>">
    </div>
    <br/>
    <label for="inputEmail" class="sr-only"><?php echo $lang->g('USERS_EMAIL'); ?></label>
    <div class="input-group">
        <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
        <input id="inputEmail" name="email" type="email" class="form-control" placeholder="<?php echo $lang->g('USERS_EMAIL'); ?>" required="" data-toggle="popover" data-placement="top" title="<?php echo $lang->g('USERS_EMAIL'); ?>" data-content="<?php echo $lang->g('USERS_EMAIL_DESCR'); ?>">
    </div>
    <br/>
    <label for="inputPassword" class="sr-only"><?php echo $lang->g('USERS_PASSWORD'); ?></label>
    <div class="input-group">
        <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
        <input id="inputPassword" name="password" type="password" class="form-control" placeholder="<?php echo $lang->g('USERS_PASSWORD'); ?>" required="" data-toggle="popover" data-placement="top" title="<?php echo $lang->g('USERS_PASSWORD'); ?>" data-content="<?php echo $lang->g('USERS_PASSWORD_DESCR'); ?>">
    </div>
    <br/>
    <label for="inputRepeatPassword" class="sr-only"><?php echo $lang->g('USERS_REPEAT_PASSWORD'); ?></label>
    <div class="input-group">
        <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
        <input id="inputRepeatPassword" name="repeatpassword" type="password" class="form-control" placeholder="<?php echo $lang->g('USERS_REPEAT_PASSWORD'); ?>" required="" data-toggle="popover" data-placement="top" title="<?php echo $lang->g('USERS_REPEAT_PASSWORD'); ?>" data-content="<?php echo $lang->g('USERS_REPEAT_PASSWORD_DESCR'); ?>">
    </div>
    <br/>
    <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> <?php echo $lang->g('USERS_REGISTER_SUBMIT'); ?></button>
</form>