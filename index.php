<?php 

require_once("vendor/autoload.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    //	echo "OK";
    $sql = new isys\DB\Sql();
    $res = $sql->select("select * from tb_addr_countries");
    echo json_encode($res);

});

$app->run();

 ?>