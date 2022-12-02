<?php
use Slim\Slim;
use isys\Page;
use isys\PageAdmin;
use isys\Model\User;

$app = new Slim();
//$app->config('debug', true);
$app->config('debug', false);


/**
 * ROTAS ESTÁTICAS PADRÃO DO SISTEMA
 */
// INDEX
$app->get('/', function() {
    $page = new Page();
    $page->setTpl("index");
});

// ADMIN
$app->get('/admin', function() {
    User::verifyLogin();

    $page = new PageAdmin();
    $page->setTpl("index",User::userTemplateData());
});

$app->get('/admin/login', function() {

    $page = new PageAdmin(array(
        "header"=>false,
        "footer"=>false
    ));
    $page->setTpl("login");
});

$app->get('/admin/logout', function() {
    User::logout();
    header("Location: /admin/login");
    exit();
});

$app->post('/admin/login', function() {
    User::login($_POST['login'],$_POST['password']);
    header("Location: /admin");
    exit();
});
/** FIM DAS ROTAS ESTÁTICAS PADRÃO DO SISTEMA */

/**
 * ROTAS DINÂMICAS
 *
 * pega o html gerado para função makeMenu() a partir da tabela do banco de dados 'tb_admin_menus'
 */
foreach (array_reverse(ISYS_MENU_ITENS) as $m_key => $m_value){

    if (in_array($m_value['des_type'],[ISYS_TYPE_MENU_PAGE,ISYS_TYPE_PAGE])){

        //$des_var = $m_value['des_href'];
        $app->get($m_value['des_href'], function($get_param) use ($m_value) {

            // Verifica se usuario está logado
            User::verifyLogin();

            // verifica se arquivo está na pasta
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/vendor/isys/Controller/".$m_value['des_file_name'].".php")){
                include_once($_SERVER['DOCUMENT_ROOT']."/vendor/isys/Controller/".$m_value['des_file_name'].".php");
            }

            // verifica se arquivo está na pasta
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/views_admin/".$m_value['des_file_name'].".html")){
                $page = new PageAdmin();
            }else{
                $page = new PageAdmin(array(
                    "header"=>false,
                    "footer"=>false
                ));
            }

            $user_template_data = User::userTemplateData();

            //parametros personalizados ($controller_data)
            if (isset($controller_data)) {
                if ($user_template_data !== NULL) {
                    $final_data = array_merge($user_template_data, $controller_data);
                } else {
                    $final_data = $controller_data;
                }
            }else{
                $final_data = $user_template_data;
            }

            $page->setTpl(
                $m_value['des_file_name']."\\",
                $final_data
            );
        });

        //se a pagina no banco tiver POST (des_have_post) executar trexos abaixo
        if ($m_value['des_have_post']=='1'){

        }
    }
}

/**
 * ROTA ERRO 404
 */
$app->notFound(function () use ($app) {
//    $app->render('404.html');
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("404",User::userTemplateData());
});

/**
 * ROTA ERRO 500
 */
$app->error(function (\Exception $e) use ($app) {
    User::verifyLogin();

    $page = new PageAdmin(array(
        "fatal_error"=>$e->getMessage()
    ));
    $page->setTpl("500");
});



$app->run();