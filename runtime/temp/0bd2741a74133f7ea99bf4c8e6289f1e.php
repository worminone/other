<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"E:\wampServe\www\tp5\public/../application/index\view\User\view.html";i:1497927370;}*/ ?>
<!DOCTYPE html>
<html>
 <head> 
  <meta charset="UTF-8" /> 
  <title>创建用户</title> 
  <style> 
body{
  	font-family:"Microsoft	Yahei","Helvetica	Neue",Helvetica,Arial,sans-serif;
  	font-size:16px;
  	padding:5px; 
  	} 
.form{
	padding:	15px;
	font-size:	16px; 
  	}
.form .text{
	padding:3px;
	margin:2px	10px;
	width:	240px;
	height:	24px;
	line-height:28px;
	border:	1px	solid #D4D4D4;
}
 .form	.btn{
	margin:6px;
	padding:	6px;
	width:	120px;
	font-size:	16px;
	border:	1px	solid	#D4D4D4;
	cursor:	pointer;
	background:#eee;
}
a{
	color:	#868686;
	cursor:	pointer; 
} 
a:hover{
	text-decoration:	underline; 
}
h2{
	color:#4288ce;
	font-weight:400;
	padding:6px	0;
	margin:	6px	0 0;
	font-size:28px;
	border-bottom:1px solid	#eee; 
} 
div{
	margin:8px; 
} 
.info{
	padding: 12px 0;
	border-bottom: 1px	solid #eee; 
}

</style> 
 </head> 
 <body> 
  <h2>创建用户</h2> 
  <form method="post" class="form" action="<?php echo url('index/index/addInfo'); ?>">
<!--     昵 称：
   <input type="text" class="text" name="name" />
   <br /> 邮 箱：
   <input type="text" class="text" name="email" />
   <br /> 年 龄： -->
   <input type="text" class="text" name="img[]" />
   <br /> 
   <input type="text" class="text" name="pic[]" />
   <br /> 
   <input type="text" class="text" name="img[]" />
   <br /> 
   <input type="text" class="text" name="pic[]" />
   <br /> 

   <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>" />
   <input type="submit" class="btn" value="提交" /> 
  </form> 

 </body>
</html>