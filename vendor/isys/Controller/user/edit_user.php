<?php
use isys\Model\User;

$user = new User();

$data_user = $user->getUser((int)$get_param);

$controller_data = array("user"=>$data_user[0]);