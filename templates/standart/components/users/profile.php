<?php defined('_YRNEXEC') or die;

$ouser = $this->ob[0];
if ($ouser['avatar']=='') $ouser['avatar'] = $this->tmpurl.'images/noavatar.jpg';
//$deb->a($ouser['lastonline']);
?>
<article>
<h1 class="page-header"><?php echo $lang->g('USERS_PROFILE_H1').': '.$ouser['login'];?></h1>
<?php //$deb->a($this->ob);?>
    <div class="users profile">
            <div class="header text-center">
                <p class="text-right"><?php
                    if ($ouser['online']===true) {
                        echo '<span class="label label-success">'.$lang->g('USERS_ONLINE').'</span>';
                    } else {
                        echo '<span class="label label-danger">'.$lang->g('USERS_OFFLINE').'</span><br/><span class="text-muted">'.$lang->g('USERS_LASTONLINE').' '.$ouser['lastonline']->format('Y-m-d H:i:s').'</span>'; //TODO: create date class
                    }
                    ?></p>
                <a href="<?php echo $ouser['link'];?>" class="user" alt="<?php echo $ouser['name'].' '.$ouser['secname'];?>">
                    <img src="<?php echo $ouser['avatar']; ?>" class="img-circle"/>
                    <h4><?php echo $ouser['name'].' '.$ouser['secname'];?></h4>
                    <span class="text-muted"><?php echo $ouser['status'];?></span>
                </a>
            </div>
        <p><?php echo $lang->g('USERS_PROFILE_WITHUS').' '.$ouser['withus']->format('<span class="label label-default">%y</span> '.$lang->g('YEARS').' <span class="label label-default">%m</span> '.$lang->g('MONTH').' <span class="label label-default">%d</span> '.$lang->g('DAYS')); ?></p>
    </div>
</article>
