<?php
namespace app\index\model;

use	think\Model;
class User extends Model {
	 protected $field  = true;
	 protected $type   = [];
	 protected $insert = ['age' => 3];
		// protected $type = [								
		// 	//	设置birthday为时间戳类型（整型）
		// 	'birthday' => 'timestamp:Y/m/d',
		// ]; 

	//查询时自动修改
	 protected function getAddTimeAttr($add_time) {
	 	return strtotime($add_time);

	 }

	 
	 //自动修改
	 protected function getAgeAttr($age) {
	 	return '1' == $age ? '男' : '女';	
	 }



	 //添加修改自动修改
	 protected function setAgeAttr($value) {
	 	if($value = '10') {
	 		return '520';
	 	}
	 }

	 //自动判断	查询
	 protected function scopeName($query) {
	 	$query->where('name', 'Faith');
	 }


}