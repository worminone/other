<?php
namespace app\index\controller;
use \think\View;
use \think\Session;
class Admin
{
    public function index()
    {
        if (!session('?ext_user')) {
            header(strtolower("location: ".config("web_root")."/index/login/login"));
            exit();
        }
        // dd(Session::has('ext_user'));
       $view = new View();
       
       return $view->fetch('index'); 
       
    }

    // 退出登录
    public function logout(){
    	\app\common\model\Admin::logout();

      if (!session('?ext_user')) {
        header(strtolower("location: ".config("web_root")."/index/login/login"));
        exit();
      }
      header(strtolower("location:login"));
      return NULL;

    	
    }
}
