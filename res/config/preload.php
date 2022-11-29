<?php

use isys\MenuAdmin;

// insert admin menu data
$menu = new MenuAdmin();
$menu_itens = $menu->getMenu();
$fmenu = $menu->makeMenu($menu_itens);

define("ISYS_MENU_ITENS", $menu_itens);
define("ISYS_FMENU", $fmenu);

?>