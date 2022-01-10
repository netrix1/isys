<?php
use Slim\Slim;
use isys\Page;
use isys\PageAdmin;
use isys\Model\User;

$app = new Slim();
//$app->config('debug', true);
$app->config('debug', false);

$app->get('/', function() {
    $page = new Page();
    $page->setTpl("index");
});

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

// add admin itens
foreach (array_reverse(ISYS_MENU_ITENS) as $m_key => $m_value){

    if (in_array($m_value['des_type'],[ISYS_TYPE_MENU_PAGE,ISYS_TYPE_PAGE])){

        $app->get($m_value['des_href'], function() use ($m_value) {

            User::verifyLogin();

            // verifica se arquivo está na pasta
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/views_admin/".$m_value['des_file_name'].".html")){
                $page = new PageAdmin();
            }else{
                $page = new PageAdmin(array(
                    "header"=>false,
                    "footer"=>false
                ));
            }


            $page->setTpl($m_value['des_file_name'],User::userTemplateData());
            exit();
        });
        if ($m_value['des_have_post']=='1'){
            // $app->post rules
        }
    }
}


$app->notFound(function () use ($app) {
//    $app->render('404.html');
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("404",User::userTemplateData());
});

$app->error(function (\Exception $e) use ($app) {
    User::verifyLogin();

    $page = new PageAdmin(array(
//        "fatal_error"=>"<h2>FATAL ERROR:<small>".$e->getMessage()."</small></h2>"
        "fatal_error"=>$e->getMessage()
    ));
//    $page = new PageAdmin();
    $page->setTpl("500",User::userTemplateData());
});



$app->run();