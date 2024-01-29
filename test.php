<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Роман
 * Date: 08.02.15
 * Time: 0:49
 * To change this template use File | Settings | File Templates.
 */

defined('_YRNEXEC') or die;

class link {
    public $var;
    public $childrens = array();
    public $parent = NULL;

    public function __construct($var,$parent=NULL) {
        $this->var = $var;
        if ($parent!==NULL) {
            $this->parent = &$parent;
            $parent->childrens[] = &$this;
        }
    }
};

class menu {
    public $mass = array();

    public function pr($lin) {
        echo '<li>'.$lin->var;
        if ($lin->childrens) {
            echo '<ul>';
            foreach ($lin->childrens as $children) {
                $this->pr($children);
            }
            echo '</ul>';
        }
        '</li>';
    }
};



$menu = new menu;
$menu->mass[0] = new link(1);
$menu->mass[1] = new link(1.1,$menu->mass[0]);
$menu->mass[2] = new link(1.2,$menu->mass[0]);
$menu->mass[3] = new link(2);

echo '<ul>';
$menu->pr($menu->mass[0]);
echo '</ul>';