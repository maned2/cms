<?php defined('_YRNEXEC') or die;
?>
<article>
<h1 class="page-header"><?php echo $lang->g('USERS_MAIN');?></h1>
<?php //$deb->a($this->ob);
if (count($this->ob)>0) {?>
    <div class="container-fluid users main">
        <?php foreach ($this->ob as $ouser) {
            if ($ouser['avatar']=='') $ouser['avatar'] = $this->tmpurl.'images/noavatar.jpg';
            ?>
            <div class="col-md-4">
                <a href="<?php echo $ouser['link'];?>" class="user" alt="<?php echo $ouser['name'].' '.$ouser['secname'];?>">
                    <img src="<?php echo $ouser['avatar']; ?>" class="img-circle"/>
                    <h4><?php echo $ouser['name'].' '.$ouser['secname'];?></h4>
                    <span class="text-muted"><?php echo $ouser['status'];?></span>
                    <span class="text-muted"><?php echo $ouser['login'];?></span>
                </a>
            </div>
        <?php }?>
    </div>
<?php } else {?>
    <h2><?php echo $lang->g('NOTHING_FOUND');?></h2>
<?php } ?>
</article>
