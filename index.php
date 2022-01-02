<?php 

require_once("vendor/autoload.php");

use Slim\Slim;
use isys\Page;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    //	echo "OK";
//    $sql = new isys\DB\Sql();
//    $res = $sql->select("select * from tb_addr_countries");
//    echo json_encode($res);

    $page = new Page();
    $page->setTpl("index");
});

$app->run();

 ?>