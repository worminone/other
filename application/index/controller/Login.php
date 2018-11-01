<?php
namespace app\index\controller;
use \think\View;
use think\Input;
use think\Request;
use think\Controller;

class Login
{
    public function login()
    {
       $view = new View();
       $name = input('request.name');
       $password  = input('request.password');


       $check=\app\common\model\Admin::login($name, $password);
       if ($check) {
       		// header(strtolower("location:"));
       		header(strtolower("location:". config("web_root") . "index/admin/index"));
			exit();
       }
       
       return $view->fetch('login'); 
    }
}
