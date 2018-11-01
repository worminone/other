<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


function dd() {
	$arr = func_get_args();
	echo '<pre>';
	foreach ($arr as $v) {
		var_dump($v);
	}
	echo '</pre>';
	exit();
}

    /**
     * 获取最近一次查询的sql语句
     * @access public
     * @return string
     */
function _sql()
{
    return $this->connection->getLastSql();
}


