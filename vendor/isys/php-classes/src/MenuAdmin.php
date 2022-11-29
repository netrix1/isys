<?php
namespace isys;
use isys\DB\Sql;

class MenuAdmin {
    public function getMenu(){
        $sql = new Sql();
        $res = $sql->select("SELECT * FROM tb_admin_menus WHERE des_inactived_status=0 ORDER BY des_father_menu, des_order ASC ");
        return $res;
    }

    public function makeMenu($menu=array(), $position="0"){
        $html = "";
        if ($position === "0") {
            $html .= "<ul class=\"navbar-nav bg-gradient-primary sidebar sidebar-dark accordion\" id=\"accordionSidebar\">";
            $html .= "
            <a class=\"sidebar-brand d-flex align-items-center justify-content-center\" href=\"index.html\">
                <div class=\"sidebar-brand-icon rotate-n-15\">
                    <i class=\"fas fa-globe\"></i>
                </div>
                <div class=\"sidebar-brand-text mx-3\">".ISYS_TITLE."</div>
                
            </a>
            ";
        }else{
            $html .= "<div id=\"collapse{$position}\" class=\"collapse\" aria-labelledby=\"headingTwo\" data-parent=\"#accordionSidebar\">";
            $html .= "<div class=\"bg-white py-2 collapse-inner rounded\">";
        }
        foreach ($menu as $k => $v){
            if ($v['des_father_menu'] === $position){
                if ($position === "0"){
                    if ($v['des_top_hr'] == "1"){$html.="<hr class=\"sidebar-divider my-0\">";}
                    if ($v['des_type'] == "1"){ //menu and page (menu whith link)
                        $html.= "<li class=\"nav-item\">";
                        $html.= "<a class=\"nav-link\" href='{$v['des_href']}'>";
                        $html.= $v['des_tag_icon'];
                        $html.= $v['des_name'];
                        $html.= "</a>";
                        $html.= $this->makeMenu($menu,$v['id_admin_menu']);
                        $html.= "</li>";
                    }elseif ($v['des_type'] == "3") { //menu (menu father)
                        $html.= "<li class=\"nav-item\">";
                        $html.= "<a class=\"nav-link\" href='{$v['des_href']}' data-toggle=\"collapse\" data-target=\"#collapse{$v['id_admin_menu']}\" aria-expanded=\"true\" aria-controls=\"collapse{$v['id_admin_menu']}\">";
                        $html.= $v['des_tag_icon'];
                        $html.= $v['des_name'];
                        $html.= "</a>";
                        $html.= $this->makeMenu($menu,$v['id_admin_menu']);
                        $html.= "</li>";
                    }elseif ($v['des_type'] == "4") { // description

                    }
                    if ($v['des_botton_hr'] == "1"){$html.="<hr class=\"sidebar-divider d-none d-md-block\">";}
                }else{
                    $html.= "<a class=\"collapse-item\" href='{$v['des_href']}'>";
                    $html.= $v['des_name'];
                    $html.= "</a>";
                }
            }
        }
        if ($position === "0") {
            $html .= "
            <div class=\"text-center d-none d-md-inline\">
                <button class=\"rounded-circle border-0\" id=\"sidebarToggle\"></button>
            </div>";
            $html .= "</ul>";
        }else{
            $html .= "</div>";
            $html .= "</div>";
        }
        return $html;
    }
}
$example_html = '
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>

        <!-- Divider -->
        

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="index.html">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Interface
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Components</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Components:</h6>
                    <a class="collapse-item" href="buttons.html">Buttons</a>
                    <a class="collapse-item" href="cards.html">Cards</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Utilities</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Utilities:</h6>
                    <a class="collapse-item" href="utilities-color.html">Colors</a>
                    <a class="collapse-item" href="utilities-border.html">Borders</a>
                    <a class="collapse-item" href="utilities-animation.html">Animations</a>
                    <a class="collapse-item" href="utilities-other.html">Other</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Addons
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item active">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Pages</span>
            </a>
            <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Login Screens:</h6>
                    <a class="collapse-item" href="login.html">Login</a>
                    <a class="collapse-item" href="register.html">Register</a>
                    <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                    <div class="collapse-divider"></div>
                    <h6 class="collapse-header">Other Pages:</h6>
                    <a class="collapse-item" href="404.html">404 Page</a>
                    <a class="collapse-item active" href="blank.html">Blank Page</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Charts</span></a>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="tables.html">
                <i class="fas fa-fw fa-table"></i>
                <span>Tables</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

';