<?php
/**
 * Created by Yakushev Roman.
 * User: Roman
 * Date: 26.01.15
 * Time: 12:13
 * To more info see: http://ykushev.ru/
 */
?>
<?php if (count($alerts->mass)) { ?>
<div id="alerts">
    <?php foreach ($alerts->mass as $alert) {?>
        <div class="alert alert-<?php echo $alert['type']?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="glyphicon <?php echo $alert['icon'];?>" aria-hidden="true"></span>
            <strong><?php echo $lang->g('ALERTS_TYPE_'.strtoupper($alert['type']));?></strong> <?php echo $alert['msg'];?>
            <?php  var_dump($alert['var']);?>
        </div>
    <?php }?>
</div>
<?php } //end if count alerts ?>