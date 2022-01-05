<?php
use Slim\Slim;
use isys\Page;
use isys\PageAdmin;
use isys\Model\User;
use Rain\Tpl;

$app = new Slim();
$app->config('debug', true);

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

$app->notFound(function () use ($app) {
//    $app->render('404.html');
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("404",User::userTemplateData());
});

$app->error(function (\Exception $e) use ($app) {
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("500",User::userTemplateData());
});

$app->run();