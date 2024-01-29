<?php
/**
 * Created
 * User: Маром
 * Date: 10.01.15
 * Time: 18:55
 */

defined('_YRNEXEC') or die;

?>
<ul class="nav navbar-nav">
    <?php foreach ($this->menu as $link ) {?>
        <li>
            <a href="<?php echo $link->link?>"><?php echo $lang->g($link->text);?></a>
        </li>
    <?php } ?>
</ul>