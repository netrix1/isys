<?php
use isys\Model\User;

$user = new User();

//$get_param = 1;
$data_user = $user->getUser((int)$get_param);

if (empty($data_user)){
    trigger_error("Usuário não encontrado", E_USER_ERROR);
}
else{

}

$controller_data = array("user"=>$data_user[0]);