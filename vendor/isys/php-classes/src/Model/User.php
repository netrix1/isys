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

    public static function listAllUsers(){
        $sql = new Sql();
        $query = "SELECT *,ur.des_role_name FROM tb_users u left join tb_users_role ur ON u.id_user_role = ur.id_user_role ORDER BY u.des_user_name";
        return $sql->select($query);
    }

    public function save(){
        $sql = new Sql();
        $sql->query("
            INSERT INTO `isys`.`tb_users` (
                `des_user_login`,
                `des_user_pass`,
                `id_user_role`,
                `id_member`,
                `des_user_name`,
                `des_create_by`,
                `des_create_on`,
                `des_inactived_status`
            ) VALUES (
                'WWW',
                '$2y$10$EakvQaFzQsgEltQizx7kPekOGVBHQaUIz4VHO5gS1Qtx0xOl2V5Ua',
                '2',
                '1',
                'FEUUU',
                '1',
                '-  -     :  :',
                NULL
            );
        ");
    }

    public function getUser($user_id)
    {
        $sql = new Sql();
        $query = "
        SELECT u.id_user, u.des_user_name, u.des_user_login, u.id_user_role, u.des_create_by, u.des_create_on, u.des_modify_by, u.des_modify_on, u.des_inactived_status, u.des_inactived_by, u.des_inactived_on, ur.des_role_name, ur.des_role_nickname, m.id_member, m.des_name, m.des_picture, m.des_gender, m.des_birth_date, m.des_temple
        FROM
        tb_users u
        LEFT JOIN tb_users_role ur ON ur.id_user_role = u.id_user_role
        LEFT JOIN tb_members m ON m.id_member = u.id_member
        WHERE u.id_user = {$user_id}
        ";
        return $sql->select($query);
    }
}