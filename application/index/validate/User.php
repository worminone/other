<?php
namespace app\index\validate;

use Think\validate;
use Think\Request;

class User extends validate{
	//自动验证
	 protected $rule = [
	 	'email' => "require|email",
	 	'name'  => 'require|min:5',
	 	'age'	=> "number|between:1,120",
	 	//'__token__' => 'token',
	 ];
	 //提示
	 protected $message  =   [
        'name.require' => '名称必须',
        'name.min'     => '名称最少5个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email.email'  => '邮箱格式错误',    
    ];

}