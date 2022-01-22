<?php
use isys\Model\User;

$users = User::listAllUsers();
$controller_data = array("users"=>$users);

