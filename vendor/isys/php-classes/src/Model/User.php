<?php

namespace isys\Model;
use \isys\DB\Sql;
use \isys\Model;

class User extends Model{

    const SESSION = "User";

    public function login($login,$password){

        $sql = new Sql();
        $res = $sql->select("SELECT * FROM tb_users WHERE des_user_login=:LOGIN",array(
            ":LOGIN"=>$login
        ));

        if (count($res)===0){
            throw new \Exception("Usuario não existe ou senha é inválida");
        }

        $data = $res[0];

        if (password_verify($password,$data['des_user_pass'])===true){

            $user = new User();
            $user->setValues($data);
            $_SESSION[User::SESSION] = $user->getValues();
            return $user;

        }else{
            throw new \Exception("Usuario não existe ou senha é inválida");
        }

    }

    public static function verifyLogin(){
        if (
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["id_user"] > 0
        ){
            header("Location: /admin/login");
            exit();
        }

    }

    public static function userTemplateData(){
        $no_show_itens = array("password","des_inactived_status","des_inactived_by","des_inactived_on");
        foreach ($_SESSION[User::SESSION] as $k => $v){
            if (!in_array($k, $no_show_itens)){

                $pre_name = substr($k,0,4);
                if ($pre_name==="des_"){
                    $new_name = substr($k,4,strlen($k));
                }else{
                    $new_name = $k;
                }
                $res[$new_name] = $v;
            }
        }
        return $res;
    }

    public static function logout(){
        $_SESSION[User::SESSION] = NULL;
    }
}