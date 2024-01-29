<?php defined('_YRNEXEC') or die; ?>
    <form method="POST" class="form-signin">
        <h2 class="form-signin-heading"><?php echo $lang->g('USERS_LOGIN_PAGE_HEADING');?></h2>
        <label for="inputLogin" class="sr-only"><?php echo $lang->g('USERS_LOGIN');?></label>
        <input id="inputLogin" name="login" type="text" class="form-control" placeholder="<?php echo $lang->g('USERS_LOGIN');?>">
        <label for="inputPassword" class="sr-only"><?php echo $lang->g('USERS_PASSWORD');?></label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="<?php echo $lang->g('USERS_PASSWORD');?>" required="">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" value="remember-me"> <?php echo $lang->g('USERS_REMEMBER_ME');?>
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">
            <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
            <?php echo $lang->g('USERS_LOGIN_SUBMIT');?>
        </button>
    </form>
