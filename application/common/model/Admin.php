<?php
namespace app\common\model;

use think\Input;

class Admin extends \think\Model
{
    public static function login($name, $password)
    {

        $where['admin_name'] = $name;
        $where['admin_password'] = md5($password);

        $user=Admin::where($where)->find();
        if ($user) {
            unset($user["password"]);
            session("ext_user", $user);
            return true;
        }else{
            return false;
        }
    }


  
    
    public static function logout(){
        session("ext_user", NULL);
        return [
            "code" => 0,
            "desc" => "退出成功"
        ]; 
    }

    
} 
 