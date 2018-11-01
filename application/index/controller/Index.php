<?php
namespace extend\PHPExcel;
namespace app\index\controller;

use think\Controller;
use app\index\model\User;
use think\Db;
use think\Request;
use think\Log;
use think\Session;
use Think\validate;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Index extends Controller
{
	// use	\traits\controller\Jump;
//    public function index($name='')
//    {	//等同3.2的 M('User')->limit(10)->select()
//        //注意：使用db助手函数默认每次都会重新连接数据库，
//        //而使用Db::name或者Db::table方法的话都是单例的。
//        //db函数如果需要采用相同的链接，可以传入第三个参数，
//        //db 不常用
//        $aa = db('User')->limit(10)->select();
//        //等同3.2的 D('User')->limit(10)->select()
//        $aa = model('User')->limit(10)->select();
//        dd(($aa));
//    	$request = Request::instance();
//    	dump($request->get());
//    	dump(input('?request.id'));
//    	echo $id = input('param.id');
//    	$aa = Db::name('member')->limit(10)->select();
//    	var_dump($aa);
//    	die();
//    	echo $name;
//  		if('thinkphp' == $name) {
//  			$this->redirect('http://www.baidu.com');
//  		} else {
//  			$this->success('Hello','test');
//  		}
//        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
//    }

    function test(Request $request) {
    	// $request = Request::instance();
    	//var_dump($request->parem());
    	echo '213';
    }

    public function add() {
        $user = new User();
        $user->name  = 'wmin';
        $user->email = 'wmin@gmail.com';
        $user->age   = '24';
        $user->sex   = 1; // 对象的方式name 被覆盖 还是数组好用
        $arr  =[
            'name'  => 'love',
            'email' => 'love@gmail.com',
            'age'   => 10,
            'sex'   => 1,
        ]; 
        // dd($user);
        if($user->save($arr)) {
            echo $user->getLastSql();
            return  "新增成功";
        } else {
            return '新增失败';
        }
    }

    public function edit() {
        $user = new User();
        $id = input("param.id");
        $arr = [
            'id'   => $id,
            'name' => 'Faith',
            'sex'  => 0 
        ];

        if(false != $user->isUpdate()->save($arr)) {
            echo $user->_sql();
            return  "更新成功";
        } else {
             echo $user->_sql();
            return '新增失败';
        }

    }

    public function addAdd() {
        $user = new User();
        $data = [   
            [
                'name' => 'Liming',
                'age'  => 22,
                'sex'  => 1
            ],
            [
                'name' => 'zhangshi',
                'age'  => 25,
                'sex'  => 0
            ]

        ];

        if( $user->saveAll($data)) {
            echo $user->_sql();
            return  "批量新增成功";
        } else {
            return  "批量新增失败";
        }

    }

    public function read($id="") {
        //new 对象 就要有model
         $user = new User;
         $info = $user->get(['id'=>1]);
         dd($info);

        // 不用直接new对象 直接可以大写的表名来实例化对象  这个也要model 其实和上面一样
        $info = User::get($id);
        $user = User::where(["name"=>'Faith'])->find();
        $list = User::all();
        foreach ($list as $key => $value) {
            $data[] = $value['name'];
        }
        $res = User::where("id", ">", "7")->select();
        foreach ($res as $key => $value) {
            $result[$key]['id']   = $value['id'];
            $result[$key]['name'] = $value['name'];

        }
        // dump($list);
        //分页
        $list = User::paginate(3);
        $list = User::where("id",'>',"7")->where('name','like','%l%')->paginate(5);
        // return [$list];
        // dd($list);
        // return ['data'=>$list, 'code'=>1, 'message'=>'获取成功'];
        // echo $this-_sql();
        // dump(Log::LogSql());
        $this->assign('count',count($list));
        $this->assign('list',$list);
        //此处和3.2 不一样  5+ fetch才是渲染到页面 等同于3.2的display ，
        // 5.0的display 相当于3.2+的show
        //fetch 需要return
        return $this->fetch('User/index');
        // var_dump($info->add_time, $user->id, $data, $result);
    }

    public function delete($id = '') {
        echo $id;
        $user = new User;
        if($user->where(['id'=>3])->delete()) {
            echo $user->_sql();
            return '删除成功';
        } else {
            echo $user->_sql();
            return '删除失败';
        }
        //delete
        // if(User::destroy($id)) {
        //     return '删除成功';
        // } else {
        //     return '删除失败';
        // }
    }


    public function create() {
        dump($this->request->token());
        return $this->fetch('User/view');

    }

    public function addInfo() {
        $user = new User;
        dump(request()->session());
        // dd(input('post.'));
        if($user->validate(true)->save(input('post.'))) {
            echo '新增成功';
        } else {
            echo $user->getError();
        }
    }

    public function joinRead() {
        // $join = [
        //     ['__USER__ u', 'a.user_id=u.id'],
        //     ['__CATEGORY__ c', 'c.id=a.class_id']
        // ];
        $list = DB::table('__ARTICLE__')
                ->alias('a')
                // ->join($join)
                ->join('__USER__ u', 'a.user_id=u.id','LEFT')
                ->join('__CATEGORY__ c', 'c.id=a.class_id','LEFT')
                ->paginate(2);
        // echo DB::table('__ARTICLE__')->_sql();
                // dd($list->toArray());
      // echo  $this->fetch();
      // return view('User/index');
        $this->assign('count',count($list));
        $this->assign('list',$list);
        //此处和3.2 不一样  5+ fetch才是渲染到页面 等同于3.2的display ，
        // 5.0的display 相当于3.2+的show
        //fetch 需要return
        return $this->fetch('User/index');
    }

    public function testSession() {
        //  赋值（当前作用域）
        Session::set('name','thinkphp');
        echo  Session::get('name');
        die();
        //  赋值think作用域
        Session::set('name','thinkphp','think');
        //  判断（当前作用域）是否赋值
        Session::has('name');
        //  判断think作用域下面是否赋值
        Session::has('name','think');
        //  取值（当前作用域）
        Session::get('name');
        //  取值think作用域
        Session::get('name','think');
        //  指定当前作用域
        Session::prefix('think');
        //  删除（当前作用域）
        Session::delete('name');
        //  删除think作用域下面的值
        Session::delete('name','think');
        //  清除session（当前作用域）
        Session::clear();
        //  清除think作用域
        Session::clear('think');
        //  赋值（当前作用域）
        Session::set('name.item','thinkphp');
        //  判断（当前作用域）是否赋值
        Session::has('name.item');
        //  取值（当前作用域）
        Session::get('name.item');
        //  删除（当前作用域）
        Session::delete('name.item');    


        //助手功能操作session  
        
        //  赋值（当前作用域）
        session('name',   'thinkphp'); 
        // 赋值think作用域 
        session('name',  'thinkphp', 'think'); 
        //判断（当前作用域）是否赋值 
        session('?name'); 
        //取值（当前作用域） 
        session('name'); 
        //取值think作用域 
        session('name',  '', 'think');

        //删除（当前作用域） 
        session('name',   null); 
        //清除session（当前作用域） 
        session(null); 
        //清除think作用域 
        session(null,    'think');

    }

    public function testApi() {
        //db获取的是数组 model 获取的是对象
        $list = DB::table('__ARTICLE__')
                ->alias('a')
                // ->join($join)
                ->join('__USER__ u', 'a.user_id=u.id','LEFT')
                ->join('__CATEGORY__ c', 'c.id=a.class_id','LEFT')
                ->select();
        // dd($list);
        //不配置default_return_type 直接json()就行了 可以是json,jsonp,xml
        return json(['data'=>$list, 'code'=>1, 'message'=>'操作成功']);

    }

    public function testCaptcha() {
        // if(!captcha_check($captcha)){
        //     echo '123';
        //  //验证失败
        // };
     //   captcha_img();
        return $this->fetch('User/testCaptcha');
    }

    public function getCaptcha() {

    }

    public function uptest() {
        return $this->fetch('User/Upload');

    }

    //文件上传提交
    public  function up(Request  $request) {
        echo ROOT_PATH.'public'.DS.'uploads';
        //获取表单上传文件
        $files = $request->file('file');
        dd($files);
        //  上传文件验证  
        // $result = $this
        //     ->validate(
        //         ['file' => $file], 
        //         ['file' => 'require|image'],
        //         ['file.require' => '请选择上传文件', 'file.image' => '非法图像文件']
        //     );
        // if (empty($file)) {
        //     $this->error('请选择上传文件');
        // }
        $itme = [];
        //移动到框架应用根目录/public/uploads/目录下
        foreach ($files as $file) {
            $info = $file
                //->validate(['ext' => 'jpg,png'])
                ->move(ROOT_PATH.'public'.DS.'uploads'); 
        if ($info) {
            $item[] = $info->getRealPath(); 
        } else {
            //上传失败获取错误信息
           return  $this->error($file->getError()); 
        }   
            
        }
        return $this->success('文件上传成功'.implode('<br/>',$item));
        
    }

    public function exportExcel($expTitle,$expCellName,$expTableData) {
        //import('Vendor.PHPExcel');
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        // vendor("PHPExcel.PHPExcel");
       // import("Vendor.PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
       // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
          // Miscellaneous glyphs, UTF-8   
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        } 

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
    public function loadExcel() {
        $xlsName  = "result";
        $xlsCell  = array(
            array('result_id','账号序列'),
            array('remark','备注')    
        );
        // $xlsModel = M('Result');
    
       // $xlsData  = $xlsModel->Field('result_id,remark')->limit(10  )->select();
        $xlsData = [
            ['result_id'=>1, 'remark'=>'11'],
            ['result_id'=>2, 'remark'=>'22']
        ];
        // dd($xlsCell,$xlsData);
        // foreach ($xlsData as $k => $v)
        // {
        //     $xlsData[$k]['sex']=$v['sex']==1?'男':'女';
        // }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);

    }

    public function test1() {
        return $this->fetch('Index/test');
    }

    public function downfile($url)
    {
        set_time_limit(0); 
        header("Content-type:text/html;charset=utf-8");
        $new_name = basename($url);
        $file = fopen ($url, "rb"); 
        if ($file) { 
            $newf = fopen ($new_name, "wb"); 
            if ($newf) 
            while(!feof($file)) { 
                fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 ); 
            } 
        } 
        if ($file) 
        { 
        fclose($file); 
        } 
    }

    public function excel()
    {

        // echo '123';
        // dd($_FILES);
        $url = 'http://or9vns8l3.bkt.clouddn.com/1500.xls'; 

        $this->downfile($url);
        // die();
        $result = $this->importExecl('./bb.doc');
    }

    public function importExecl($file){
        $result = array();
        $objPHPExcel = new \PHPExcel();
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($file)){
                return false;
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        // 获取数据数组
        $info = array();        
        for ($row = 1; $row <= $line; $row ++) {
            for ($column = 'A'; $column <= $end; $column ++) {                
                $val = $cur->getCellByColumnAndRow(ord($column) - 65, $row)->getValue();
                $info[$row][] = $val;
            }
        }
        $data = array();
        for ($i = 2; $i <= count($info); $i ++) {
            for ($j = 0; $j < count($info[$i]); $j ++) {
                for ($k = 0; $k < count($info[1]); $k ++) {
                    $data[$i][$info[1][$k]] = $info[$i][$k];
                }
            }
        }

        $datalist = array_values($data);
        dd($datalist);
        $result = $DB->addAll($datalist);
        // echo $DB->getLastSql();exit;
        if ($result) {
            return true;
        }
        return false;
    }

    public function qiniuSDK() {
        //https://github.com/qiniu/php-sdk
        //composer require qiniu/php-sdk
        $accessKey = 'd0DMhHn5jAE_gP6lV5M06loB6i-L_aNqzxQiTvBF';
        $secretKey = 'IrwkLaA0WDkSwcxE6BQF8rsqgX9PBbzFlBKNB9dq';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'demo';
        $token = $auth->uploadToken($bucket);
        var_dump($token);
    }
}
