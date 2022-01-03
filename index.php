<?php 

require_once("vendor/autoload.php");

use Slim\Slim;
use isys\Page;
use isys\PageAdmin;

$app = new Slim();

$app->config('debug', true);

/*$app->get('/', function() {
    $sql = new isys\DB\Sql();
    $res = $sql->select("select * from tb_addr_countries");
    echo json_encode($res);
});*/

$app->get('/', function() {
    $page = new Page();
    $page->setTpl("index");
});

$app->get('/admin', function() {
    $page = new PageAdmin();
    $page->setTpl("index");
});

$app->run();

 ?>