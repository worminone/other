<?php
namespace app\index\controller;
use QL\QueryList;
// use QL\phpQuery;
// use jaeger\phpquerySingle\phpQuery;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Response;
use think\Db;
class PhpQuery

{
    public function index()
    {
        $hj = QueryList::Query('http://mobile.csdn.net/', array("url" => array('.unit h1 a', 'href')));
        $data = $hj->getData(function ($x) {
            return $x['url'];
        });
        dd($hj, $data);
    }

    public function inputExcel()
    {

        $objPHPExcel = new \PHPExcel();
        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle("demo");//可以给sheet设置名称为"demo"
        // $objSheet->setCellValue("A1","姓名")->setCellValue("B1","分数");  
        // $objSheet->setCellValue("A2","张三")->setCellValue("B2","100");  
        $array = array(array('姓名', '分数'), array('张三', '60'), array('李四', '61'), array('王五', '62'),);
        $objSheet->fromArray($array);//数据较大时，不建议使用此方法，建议使用setCellValue()
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//生成一个Excel2007文件  
        $objWriter->save('F:/test.xlsx');//保存文件   
    }

    public function exportExcel($file)
    {
//        $file = "G:/excel/阿坝师范学院.xlsx";
        $file = iconv("UTF-8", "GB2312//IGNORE", $file);
        copy($file,'G:/excel_bak/aa.xls');
        $file = 'G:/excel_bak/aa.xls';
        $result = array();
        $objPHPExcel = new \PHPExcel();
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($file)) {
                return false;
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        // 获取数据数组
        $info = array();
        for ($row = 1; $row <= $line; $row++) {
            for ($column = 'A'; $column <= $end; $column++) {
                $val = $cur->getCellByColumnAndRow(ord($column) - 65, $row)->getValue();
                $val = (string)$val;
                $info[$row][] = $val;
            }
        }

        $data = array();
        for ($i = 2; $i <= count($info); $i++) {
            for ($j = 0; $j < count($info[$i]); $j++) {
                for ($k = 0; $k < count($info[1]); $k++) {
                    $data[$i][$info[1][$k]] = $info[$i][$k];
                }
            }
        }
        $datalist = array_values($data);
        return $datalist;
        dd($datalist);

    }

    public function makeData()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $handler = scandir('G:/excel/');

//        dd($handler);
//        $handler = [0=>',', '1'=>'..','3'=>$handler[5]];
        foreach ($handler as $key => &$value) {
            $value = iconv("gb2312", "utf-8", $value);
        }

        $excel_title = (array_slice($handler, 2));
        foreach ($excel_title as $kkk => &$vvv) {
            $excel_url[$kkk] = 'G:/excel/' . $vvv;
        }

        $college = $excel_title;
        foreach ($college as $kk => &$vv) {
            if (strstr($vv, '.xlsx') !== false) {
                $vv = strstr($vv, '.xlsx', true);
                if (strstr($vv, '2017') !== false) {
                    $vv = strstr($vv, '2017', true);
                }
            }
        }
        foreach ($excel_url as $kkkk => &$vvvv) {
//            $file = $vvvv;
//            $file = "G:/excel/阿坝师范学院.xlsx";
            $data = $this->exportExcel($vvvv);
            $datas = '';
            foreach ($data as $k => &$v) {
//                echo '<pre>';
//                print_r($v);
//                print_r($vvvv);
//                print_r($college[$kkkk]);
//                die();
                if (!empty($v['年份'])) {
                    $datas[$k]['year'] = $v['年份'];
                    $datas[$k]['college'] = $college[$kkkk];
                    $datas[$k]['province'] = $v['省份'];
                    $datas[$k]['batch'] = $v['批次'];
                    $datas[$k]['science'] = $v['科类'];
                    $datas[$k]['department'] = $v['院系'];
                    $datas[$k]['major_type'] = $v['大类招生'];
                    $datas[$k]['major'] = $v['录取专业'];
                    $datas[$k]['direction'] = $v['专业方向'];
                    $datas[$k]['max'] = $v['最高分'];
                    $datas[$k]['min'] = $v['最低分'];
                    $datas[$k]['avg'] = $v['平均分'];
                }

//                unset($data[$k]['年份']);
//                unset($data[$k]['省份']);
//                unset($data[$k]['批次']);
//                unset($data[$k]['科类']);
//                unset($data[$k]['院系']);
//                unset($data[$k]['大类招生']);
//                unset($data[$k]['录取专业']);
//                unset($data[$k]['专业方向']);
//                unset($data[$k]['最高分']);
//                unset($data[$k]['最低分']);
//                unset($data[$k]['平均分']);
//                unset($data[$k]['备注']);
//                unset($data[$k]['']);
            }
//            echo '<pre>';
//            print_r($datas);
//            die();

            db('college_major_score')->insertAll(array_values($datas));
            $vvvv = iconv("UTF-8", "GB2312//IGNORE", $vvvv);
            unlink($vvvv);
        }

    }

    public function importScore()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $ss_where['status'] = 0;
//        $ss_where['id'] = ['<=', '10'];
        $list = Db::name('college_major_score')->where($ss_where)->limit(5000)->select();
        echo Db::name('college_major_score')->getLastSql();
//        dd($list);

        foreach ($list as $key => $value) {
            $info = $infos = $a_info = '';
            $where['region_name'] = ['like',trim($value['province']).'%'];
            $where['parent_id'] = 0;
            $c_info = Db::name('College')->where(['title'=>$value['college']])->find();
            if(empty($c_info)) {
                Db::name('college_major_score')->where(['id'=>$value['id']])->update(['status'=>3]);
                continue;
            }
//            $m_info = Db::name('MajorInfo')->where(['majorName'=>$value['majorname']])->find();
            $m_where['majorTypeName'] = $value['major_type'];
            $m_where['level'] = 2;
            $t_info = Db::name('MajorType')->where($m_where)->find();
            $mn_where['majorTypeName'] = $value['major'];
            $mn_where['level'] = 3;
            $mn_info = Db::name('MajorType')->where($mn_where)->find();
            if(empty($t_info)  && empty($mn_info)) {
                Db::name('college_major_score')->where(['id'=>$value['id']])->update(['status'=>2]);
                continue;
            }
//            echo Db::name('MajorType')->getLastSql();
            $r_info = Db::name('Region')->where($where)->find();
//             dd($where,$r_info);
//            if($m_info) {
            $info['college_id'] = $c_info['college_id'];
            if( $t_info ) {
                $info['majorName'] = $t_info['majorTypeName'];
                $info['majorNumber'] = $t_info['majorTypeNumber'];
                $info['majorTypeNumber'] = $t_info['majorTypeNumber'];
                $info['majorTypeName'] = $t_info['majorTypeName'];
                $type_id = $t_info['type_id'];
            }
            if ($mn_info){
                $info['majorName'] = $mn_info['majorTypeName'];
                $info['majorNumber'] = $mn_info['majorTypeNumber'];
                $mt_info = Db::name('MajorType')->where(['majorTypeNumber'=>$mn_info['majorTopTypeNumber']])->find();
                $info['majorTypeNumber'] = $mt_info['majorTypeNumber'];
                $info['majorTypeName'] = $mt_info['majorTypeName'];
                $type_id = $mt_info['type_id'];
            }
            $info['type_id'] = $type_id;
//                $info['major_id'] = $m_info['major_id'];
            $info['department_id'] = 0;
            $info['department_name'] = $value['department'];
            $info['province_id'] = $r_info['region_id'];
            $info['province'] = $r_info['region_name'];
            $info['enrollmentYear'] = $value['year'];
            $info['batch'] = $value['batch'];
            $info['direction'] = $value['direction'];

            $s_where['direction'] = $value['direction'];
            $s_where['enrollmentYear'] = $info['enrollmentYear'];
            $s_where['college_id'] = $info['college_id'];
            $s_where['majorNumber'] = $info['majorNumber'];
            $s_where['province_id'] = $info['province_id'];
            $s_where['batch'] = $info['batch'];
            $s_where['department_name'] = $info['department_name'];

            $e_info = Db::name('CollegeMajorEnroll')->where($s_where)->find();
//                dd($info,$value);
            if($e_info) {
                $id = $e_info['id'];
            } else {
                $id = Db::name('CollegeMajorEnroll')->insertGetId($info);
            }

            $infos['enroll_id'] = $id;
            $infos['science'] = $value['science'];

            $infos['max'] =round($value['max']);
            $infos['min'] =round($value['min']);
            $infos['avg'] =round($value['avg']);
            if($value['science'])
                $a_info = Db::name('CollegeAdmissionScore')->where(['enroll_id'=>$id, 'science'=>$value['science']])->find();
            if($a_info) {
                $infos['id'] = $a_info['id'];
//                    if($infos['id'] == '7078166') {
//                        $id = Db::name('CollegeAdmissionScore')->update($infos);
//                        echo Db::name('CollegeAdmissionScore')->getLastSql();
//                        dd($infos,$a_info);
////
////                        die();
//                    }
//                    $id = Db::name('CollegeAdmissionScore')->update($infos);
            } else {
//                    if($infos['id'] == '7078163') {
//                        $id = Db::name('CollegeAdmissionScore')->update($infos);
//                        echo Db::name('CollegeAdmissionScore')->getLastSql();
//                        dd($infos,$a_info);
////
//                        die();
//                    }
                $id = Db::name('CollegeAdmissionScore')->insertGetId($infos);
            }
            $data['status'] = 1;
            Db::name('college_major_score')->where(['id'=>$value['id']])->update($data);
//            }else{
//                $data['status'] = 2;
//                Db::name('specialty_score_v3')->where(['id'=>$value['id']])->update($data);
//                continue;
//            }
            echo '添加ID'.$id.'<br>';
        }
        $count = Db::name('college_major_score')->where(['status'=>0])->count();
        echo '新增结束,剩下数量'.$count;

    }

    public function  setDepartment()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $sql = "SELECT MAX(id),department_name,college_id FROM `dd_college_major_enroll` WHERE id > 4130510 AND department_name != '' GROUP BY department_name,college_id";
        $list = Db::query($sql);
        foreach ($list as $key => $value)
        {
            $where['department_name'] = $value['department_name'];
            $where['college_id'] = $value['college_id'];
            $info = Db::name('college_department')->where($where)->find();
            if(!$info)
            {
//                dd($info);

                $data['department_name'] = $value['department_name'];
                $data['college_id'] = $value['college_id'];
                $data['create_time'] = time();
                $id = Db::name('college_department')->insertGetId($data);
                $dats['department_id'] = $id;
                $res = Db::name('college_major_enroll')->where($where)->update($dats);
                echo $res.'<br>';
            } else {
//                dd($where);
                $dats['department_id'] = $info['id'];
                $res = Db::name('college_major_enroll')->where($where)->update($dats);
                echo  Db::name('college_major_enroll')->getLastSql();
                echo $res.'<br>';
//                dd($res);
            }
        }
    }


    public function qrCode()
    {
        // Create a basic QR code
        $qrCode = new QrCode('www.baidu.com');
        $qrCode->setSize(300);
        // dd('./vendor/endroid/qrCode/assets/noto_sans.otf');

        // Set advanced options
        $qrCode->setWriterByName('png')->setMargin(10)->setEncoding('UTF-8')->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])// ->setLabel('Scan the code', 16, 'F:\wamp64\www\tp5\vendor\endroid\qrcode\assets/noto_sans.otf', LabelAlignment::CENTER)
            // ->setLogoPath('F:\wamp64\www\tp5\vendor\endroid\qrcode\assets/symfony.png')
            ->setLogoWidth(150)->setValidateResult(false);

        // Directly output the QR code
        header('Content-Type: ' . $qrCode->getContentType());
        //  echo $qrCode->writeString();

        // Save it to a file
        $qrCode->writeFile(__DIR__ . '/qrcode.png');

        // Create a response object
        // $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }

    public function uploadImg()
    {
        $urls = QueryList::run('Request', ['target' => 'http://cms.querylist.cc/news/list_2.html', 'referrer' => 'http://cms.querylist.cc', 'method' => 'GET', 'params' => ['var1' => 'testvalue', 'var2' => 'somevalue'], 'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0', 'cookiePath' => './cookie.txt', 'timeout' => '30'])->setQuery(['link' => ['h2>a', 'href', '', function ($content) {
            //利用回调函数补全相对链接
            $baseUrl = 'http://cms.querylist.cc';
            return $baseUrl . $content;
        }]], '.cate_list li')->getData(function ($item) {
            return $item['link'];
        });

        //多线程扩展
        QueryList::run('Multi', ['list' => $urls, 'curl' => ['opt' => array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_FOLLOWLOCATION => true, CURLOPT_AUTOREFERER => true,), //设置线程数
            'maxThread' => 100, //设置最大尝试数
            'maxTry' => 3], 'success' => function ($a) {
            //采集规则
            $reg = array(//采集文章标题
                'title' => array('h1', 'text'), //采集文章发布日期,这里用到了QueryList的过滤功能，过滤掉span标签和a标签
                'date' => array('.pt_info', 'text', '-span -a', function ($content) {
                    //用回调函数进一步过滤出日期
                    $arr = explode(' ', $content);
                    return $arr[0];
                }), //采集文章正文内容,利用过滤功能去掉文章中的超链接，但保留超链接的文字，并去掉版权、JS代码等无用信息
                'content' => array('.post_content', 'html', 'a -.content_copyright -script', function ($content) {
                    //利用回调函数下载文章中的图片并替换图片路径为本地路径
                    //使用本例请确保当前目录下有image文件夹，并有写入权限
                    //由于QueryList是基于phpQuery的，所以可以随时随地使用phpQuery，当然在这里也可以使用正则或者其它方式达到同样的目的
                    $doc = phpQuery::newDocumentHTML($content);
                    $imgs = pq($doc)->find('img');
                    foreach ($imgs as $img) {
                        $src = pq($img)->attr('src');
                        $localSrc = 'image/' . md5($src) . '.jpg';
                        $stream = file_get_contents($src);
                        file_put_contents($localSrc, $stream);
                        pq($img)->attr('src', $localSrc);
                    }
                    return $doc->htmlOuter();
                }));
            $rang = '.content';
            $ql = QueryList::Query($a['content'], $reg, $rang);
            $data = $ql->getData();
            //打印结果，实际操作中这里应该做入数据库操作
            print_r($data);
        }]);
    }

    public function Caiji()
    {
        // 采集该页面[正文内容]中所有的图片
        $data = QueryList::get('http://cms.querylist.cc/bizhi/453.html')->find('.post_content img')->attrs('src');
        //打印结果
        print_r($data->all());


        // 采集该页面文章列表中所有[文章]的超链接和超链接文本内容
        $data = QueryList::get('http://cms.querylist.cc/google/list_1.html')->rules(['link' => ['h2>a', 'href', '', function ($content) {
            //利用回调函数补全相对链接
            $baseUrl = 'http://cms.querylist.cc';
            return $baseUrl . $content;
        }], 'text' => ['h2>a', 'text']])->range('.cate_list li')->query()->getData();
        //打印结果
        print_r($data->all());
    }


    public function insertMajor($page)
    {
        $limit = 100;
        $list = Db::name('college_score_70w')->limit($limit * ($page - 1), $limit)->order($order)->select();
        foreach ($list as $key => $value) {
            $where['region_name'] = ['like', $value['province'] . '%'];
            $where['parent_id'] = 0;
            $c_info = DB::name('College')->where(['title' => $value['college']])->find();
            $m_info = DB::name('MajorInfo')->where(['majorName' => $value['major']])->find();
            $r_info = DB::name('Region')->where($where)->find();
        }
    }

    public function getMajorFile($majorName)
    {
        $dir = 'G:/music/occupation';

        // $majorName = iconv("GB2312","UTF-8//IGNORE",$majorName);
        // $url = $value['audiourl'];
        $url = 'C:/Users/24595/Desktop/pic_2/' . $majorName . '.png';
        $start = strripos($url, '/') + 1;
        $end = strripos($url, '.') - $start;
        // echo $start;
        // die();
        $title = substr($url, $start, $end);
        $title = md5($title);
        $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.png');
        $url = iconv("UTF-8", "GB2312//IGNORE", $url);
        // dd($url, $dir, $title);
        $this->grabImage($url, $dir, $title);
    }

    public function getXX()
    {
        $list = db('MajorType')->where(['level' => 3])->select();
        foreach ($list as $key => $value) {
            $majorNameCn = $value['majorTypeName'];

            $major_name = md5($value['majorTypeName']);
            $title = iconv("UTF-8", "GB2312//IGNORE", $value['majorTypeName']);
            $url = 'C:/Users/24595/Desktop/pic_1/' . $title . '.png';
            $xx = 'http://image.zgxyzx.net/' . $major_name . '.png';
            $info['pic_url'] = $xx;
            // dd(file_exists($url),$url);
            if (file_exists($url)) {
                db('MajorType')->where(['type_id' => $value['type_id']])->update($info);
                db('MajorInfo')->where(['type_id' => $value['type_id']])->update($info);
                $this->getMajorFile($majorNameCn);
                $url = 'C:/Users/24595/Desktop/pic_1/' . $majorNameCn . '.png';
                $url = iconv("UTF-8", "GB2312//IGNORE", $url);
                unlink($url);
                // die();
            } else {
                continue;
            }
        }
    }


    public function getOcc()
    {
        $list = db('occupation_info')->select();
        // dd($list);
        foreach ($list as $key => $value) {
            $majorNameCn = $value['occupation_name'];

            if (strstr($majorNameCn, '/')) {
                $start = strripos($majorNameCn, '/') + 1;

                // $end = strripos($majorNameCn,'.')-$start;
                $new_title = substr($majorNameCn, $start);
                // dd($new_title, $start);
            } else {
                $new_title = $value['occupation_name'];
            }
            // dd($new_title);
            $major_name = md5($new_title);
            $title = iconv("UTF-8", "GB2312//IGNORE", $value['occupation_name']);
            $url = 'C:/Users/24595/Desktop/pic_2/' . $title . '.png';
            $xx = 'http://image.zgxyzx.net/' . $major_name . '.png';
            $info['pic_url'] = $xx;
            // dd(file_exists($url),$url);
            if (file_exists($url)) {
                db('occupation_info')->where(['occupation_id' => $value['occupation_id']])->update($info);
                $this->getMajorFile($majorNameCn);
                $url = 'C:/Users/24595/Desktop/pic_2/' . $majorNameCn . '.png';
                $url = iconv("UTF-8", "GB2312//IGNORE", $url);
                // unlink($url);
                // die();
            } else {
                continue;
            }
        }
    }

    public function getScore()
    {
        // $list = DB::name('score_17')->select();
        // foreach ($list as $key => $value) {
        //     $where['collegeName'] = $value['sch_name'];
        //     // $where['department_id'] = $value[''];
        //     $where['province'] = $value['province'];
        //     $where['enrollmentBatch'] = $value['batch'];
        //     $where['enrollmentYear'] = $value['year'];
        //     $info = DB::name('college_score')->where($where)->find()
        //     // if (!$info) {
        //     //     $data[$key][''] = $value['sch_name'];
        //     //     $data[$key][''] = $value['wenli'];
        //     //     $data[$key][''] = $value['province'];
        //     //     $data[$key][''] = $value['year'];
        //     //     $data[$key][''] = $value[''];
        //     //     $data[$key][''] = $value[''];
        //     //     $data[$key][''] = $value[''];
        //     //     $data[$key][''] = $value[''];
        //     //     $data[$key][''] = $value[''];
        //     // }
        // }

        set_time_limit(0);
        $limit = 25000;
        $page = 1;
        $list = db('score_18')->limit($limit * ($page - 1), $limit)->where(['status' => 0])->order('id desc')->select();
        $data = $ids = [];
        foreach ($list as $key => $value) {
            $college_info = db('College')->where(['title' => $value['sch_name']])->find();
            if ($college_info) {
                $where['college_id'] = $college_info['college_id'];
                $where['province'] = $value['province'];
                $where['science'] = $value['wenli'];
                $where['enrollmentBatch'] = $value['batch'];
                $where['enrollmentYear'] = $value['year'];
                $info = db('CollegeScore')->where($where)->find();
                $r_where['region_name'] = ['like', $value['province'] . '%'];
                $r_where['parent_id'] = 0;
                $r_info = db('Region')->where($r_where)->find();
                // dd($info);
                if (!$info && $college_info['collegeCode'] != '') {
                    $data[$key]['college_id'] = $college_info['college_id'];
                    $data[$key]['collegeCode'] = $college_info['collegeCode'];
                    $data[$key]['collegeName'] = $college_info['title'];
                    $data[$key]['province'] = $value['province'];
                    $data[$key]['province_id'] = $r_info['region_id'];
                    $data[$key]['science'] = $value['wenli'];
                    $data[$key]['enrollmentBatch'] = $value['batch'];
                    $data[$key]['enrollmentYear'] = $value['year'];
                    $data[$key]['max'] = $value['max_score'];
                    $data[$key]['min'] = $value['min_score'];
                    $data[$key]['avg'] = $value['avg_score'];
                    $data[$key]['shengkong'] = $value['prov_toudang_score'];
                    $data[$key]['numberOfAdmissions'] = 0;
                    $ids[] = $value['id'];
                    // // dd($data);
                    // $id = db('CollegeScore')->insertGetId($data);
                    // db('score_17')->where(['id'=>$value['id']])->update(['status'=>1]);
                    // echo '更新ID=' .$id. '<br/>';
                }
            }
        }
        // dd($data);
        if (count($data) > 0) {
            $data = array_values($data);
            $num = db('CollegeScore')->insertAll($data);
            db('score_18')->where('id', 'in', $ids)->update(['status' => 1]);
            echo '更新' . $num . '<br/>';
        } else {
            echo 'ces';
        }

    }














    /*====================================================================================================================================================================================================================================================*/


    /*
     *@$url string 远程图片址
     *@$dir string 目录选 默认前目录（相路径）
     *@$filename string 新文件名选
     */
    function grabImage($url, $dir = '', $filename = '')
    {
        if (empty($url)) {
            return false;
        }
        $ext = strrchr($url, '.');
        // if($ext != '.gif' && $ext != ".jpg" && $ext != ".bmp"){
        //     echo "格式支持";
        //     return false;
        // }
        //空前目录
        if (empty($dir)) $dir = './';

        // $dir = realpath($dir);
        $dir = iconv('utf-8', 'gbk', $dir);
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        //目录+文件
        $filename = $dir . (empty($filename) ? '/' . time() . $ext : '/' . $filename);
        //始捕捉 
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        $fp2 = fopen($filename, "a");
        fwrite($fp2, $img);
        fclose($fp2);
        // echo $filename;
        return $filename;
    }
    //测试 
    // PS：目录存权限判断自创建等自应该知道
    //喜欢绝路径所写

    public function uploadMp3()
    {


        $data = json_decode($data, true);
        /* //专题*/
//        $dir_name = $data['album']['albumname'];
//        $max_img = $data['album']['imgurl'];
//        $min_img = $data['album']['simgurl'];
//
//        // $dir = 'G:/music/新歌首发鹿晗VS杨洋';
//        $dir = 'G:/music/' . $dir_name;
//        $this->grabImage($max_img, $dir, 'dd.jpg');
//        $this->grabImage($min_img, $dir, 'xx.jpg');
        //专题
//        foreach ($data['album']['reslist'] as $key => $value) {
//            $url = $value['audiourl'];
//            $title = $value['title'];
//            $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.mp3');
//            $this->grabImage($url, $dir, $title);
//            echo $value['title'] . '.mp3 == 已经更新<br/>';
//        }
        /*热门*/
          $dir = 'G:/music/最新铃声522';
          foreach ($data['wks'] as $key => $value) {
              $url = $value['audiourl'];
              $title = $value['title'];
              $title = iconv("UTF-8","GB2312//IGNORE",$title.'.mp3');
              $this->grabImage($url, $dir, $title);
              echo $value['title'].'.mp3 == 已经更新<br/>';
          }

        echo '== 已经完成<br/>';
    }

    public function getcol()
    {
        $url = 'http://api.kuyinyun.com/p/q_colres?a=53c4fbd8efb8a597&id=104561&px=0&ps=20';
        $content = file_get_contents($url);
        $data = json_decode($content, true);

        $dir = 'G:/music/11';

        foreach ($data['data'] as $key=>$val)
        {
            $audiourl = $val['audiourl'];
            $title = $val['title'];
            $title = iconv("UTF-8","GB2312//IGNORE",$title.'.mp3');
            $this->grabImage($audiourl, $dir, $title);
            echo $val['title'].'.mp3 == 已经更新<br/>';
        }
    }

    public function getRingBox()
    {
        $data = '{"data":{"list":[{"name":"青春纪念册","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/04/27/1609221016494112.jpg?m","ringid":"1296676","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"093358f325ac2cc4e74aca9c5e41daba","playtime":284,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1296676","isvip":"0","songer":"可米小子","isringing":1,"isbell":1,"ishot":"0","rtime":1468046828000,"issearch":0},{"name":"毕业歌","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/09/29/1609291724095755.jpg?m","ringid":"3558149","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"586a525499286a4e6215c7b09deb4913","playtime":307,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3558149","isvip":"0","songer":"额尔古纳乐队","isringing":1,"isbell":1,"ishot":"0","rtime":1502325928000,"issearch":0},{"name":"凤凰花开的路口","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/08/17/1609221119267758.jpg?m","ringid":"1455103","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a7246919c0a269054b5ca4b656e6e310","playtime":253,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1455103","isvip":"0","songer":"林志炫","isringing":1,"isbell":1,"ishot":"0","rtime":1468070359000,"issearch":0},{"name":"年年","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/28/452feb3737364eaba55886c370d1d3f2.jpg?m","ringid":"4185064","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a38968c66133f48f9bf","playtime":207,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4185064","isvip":"0","songer":"徐秉龙","isringing":1,"isbell":1,"ishot":"0","rtime":1525812046000,"issearch":0},{"name":"青春故事","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/31/c59dbfa1f98c4859b85846d1bcfb1ea4.jpg?m","ringid":"4252916","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"456ea643cd4dcf79b881cf0219471553","playtime":278,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4252916","isvip":"0","songer":"成龙","isringing":1,"isbell":1,"ishot":"1","rtime":1527741601000,"issearch":0},{"name":"青春再见","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2013/11/27/131113151011785.jpg?m","ringid":"1692575","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4691039e4c3a9425ec44648f61bda8e7","playtime":230,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1692575","isvip":"0","songer":"华晨宇,白举纲,左立,张阳阳","isringing":0,"isbell":0,"ishot":"0","rtime":1468107847000,"issearch":0},{"name":"纸短情长","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/03/30/1610261442206432.jpg?m","ringid":"4216097","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d5af8833a3e50c899153aad9a68466da","playtime":186,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4216097","isvip":"0","songer":"花粥","isringing":1,"isbell":1,"ishot":"1","rtime":1526665230000,"issearch":0},{"name":"我不想说再见","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/12/327dad7b3dd94aada4bfa1d87b0714d8.jpg?m","ringid":"2655318","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b34c15896a53355c5b727ba062af174d","playtime":308,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2655318","isvip":"0","songer":"牛奶咖啡","isringing":1,"isbell":1,"ishot":"0","rtime":1473476732000,"issearch":0},{"name":"别忘了","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/11/08/1609222049292730.jpg?m","ringid":"1919555","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"38044029059e9635c34ebf4f5bf48312","playtime":274,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1919555","isvip":"0","songer":"周笔畅","isringing":0,"isbell":0,"ishot":"0","rtime":1468155598000,"issearch":0},{"name":"花开那年","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2014/12/22/1412221022027263.jpg?m","ringid":"1692594","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4691039e4c3a9425ec29051aefdc4944","playtime":290,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1692594","isvip":"0","songer":"魏晨","isringing":1,"isbell":1,"ishot":"0","rtime":1468107850000,"issearch":0},{"name":"十七岁的夏天","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/22/ac8b58a729e64181ba36d7405efe75d5.jpg?m","ringid":"4228206","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"2d9ea9919a4dcf9e871623f2c14ff992","playtime":284,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4228206","isvip":"0","songer":"钟易轩","isringing":1,"isbell":1,"ishot":"0","rtime":1526978412000,"issearch":0},{"name":"骊歌(电影《少年班》插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/12/20/59c4b3c9be944337ac26548732596c53.jpg?m","ringid":"2429734","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"6727799d5e01787a125c6bc13eef3e6c","playtime":148,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2429734","isvip":"0","songer":"GALA","isringing":1,"isbell":1,"ishot":"0","rtime":1468252184000,"issearch":0},{"name":"送你一匹马(网剧《北京女子图鉴》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/27/2687194605654759bc1d4b1ecb8b3130.jpg?m","ringid":"4095698","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5db349d34e01ac7ae86","playtime":227,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4095698","isvip":"0","songer":"金志文","isringing":1,"isbell":1,"ishot":"0","rtime":1522340825000,"issearch":0},{"name":"最后一个夏天","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/23/1b568896fbf6453fbe8ccfc56737b612.jpg?m","ringid":"1290677","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f6b0e4e46a60fdde21bbd7911d5d65c3","playtime":235,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1290677","isvip":"0","songer":"金莎","isringing":0,"isbell":0,"ishot":"0","rtime":1468045974000,"issearch":0},{"name":"Hall Of Fame","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2015/11/23/1511231340523399.jpg?m","ringid":"1582067","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"defbe53182c42a0442a45e2aaf3469ef","playtime":201,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1582067","isvip":"0","songer":"The Script","isringing":1,"isbell":1,"ishot":"0","rtime":1468090193000,"issearch":0},{"name":"毕业后你不是我的","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2016/07/18/1505221015119264.jpg?m","ringid":"2930058","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"847c9cb3fbf6dcf180b2b037249d32d4","playtime":267,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2930058","isvip":"0","songer":"孙子涵","isringing":1,"isbell":1,"ishot":"0","rtime":1483616934000,"issearch":0},{"name":"境外","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/03/08/160922235159752.jpg?m","ringid":"2832940","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5efccfcb2cc3fa68b25da896576ad04d","playtime":283,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2832940","isvip":"0","songer":"莫文蔚","isringing":1,"isbell":1,"ishot":"0","rtime":1479147697000,"issearch":0},{"name":"我们的明天","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/11/02/1609222354266509.jpg?m","ringid":"2680519","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"aa253475098b305f90db65cd028c41ee","playtime":232,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2680519","isvip":"0","songer":"鹿晗","isringing":1,"isbell":1,"ishot":"0","rtime":1473479368000,"issearch":0},{"name":"你曾是少年(电影少年班主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/03/14/1505201125026996.jpg?m","ringid":"1926557","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"49cacac4b6cced466563ad8cbce0db7d","playtime":266,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1926557","isvip":"0","songer":"S.H.E","isringing":0,"isbell":0,"ishot":"0","rtime":1468156613000,"issearch":0},{"name":"后来的我们","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/06/13/c668bd20a0754c3aa0ea062aee31210b.jpg?m","ringid":"3795395","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"3779047a320e77bf178ab16d5f40992c","playtime":346,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3795395","isvip":"0","songer":"五月天","isringing":1,"isbell":1,"ishot":"0","rtime":1502975484000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"9","list_name":"毕业各奔东西，青春永不散场","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1528/16/95/1528169577237.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1433","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1528/16/95/1528169591865.png","list_content":"六月如期而至，又是一年毕业季。宿舍里嬉笑打闹的身影，教室里努力奋斗的背影，一切场景都还宛如昨日。但转眼却已准备踏上新的征程，千言万语，却不知从何说起。我们就这样，各自奔天涯。青春再见，青春再不见！","total_count":30,"list_count":20},"hash":"","code":1,"msg":"","timespan":1529913692292}';
        $data = json_decode($data, true);
    }


    public function aa()
    {
        $map_subject = ['不限'];
        $subject = ['不限', '历史', '地理', '生物', '化学', '政治', '物理', '技术(浙江)', '技术（浙江）', '技术'];
        if (array_diff($map_subject, $subject)) {
            $major_names = 1;
            echo '123';

        } else {
            echo '22';
        }
    }

    public function test_array_merge()
    {
        $a = ['a'];
        $b= ['c','a'];
        $data = array_intersect($a, $b);
        dd($data);
    }
    public function updateData()
    {
        $list = db('video')->select();
        $data  = [];
        foreach ($list as $key=>$value) {
            $data[$key]['term_type'] = $value['term_type'];
            $data[$key]['category_id'] = $value['category_id'];
            $data[$key]['cover'] = $value['cover'];
            $data[$key]['video_id'] = $value['id'];
            $data[$key]['status'] = $value['status'];
            $data[$key]['sort'] = $value['sort'];
            $data[$key]['publish_time'] = strtotime($value['create_time']);
        }
        db('term_video')->insertAll($data);
    }


    public function scoreMajormerge()
    {
        $mc_info = db('college_major_enroll_copy')->select();
        foreach ($mc_info as $key=>$value) {
            $cs_info ='';

            $score_id = $value['id'];
            $cs_info = db('college_admission_score_copy')->where(['enroll_id'=>$score_id])->select();

            unset($value['id']);

            $id = db('college_major_enroll')->insertGetId($value);

            foreach ($cs_info as $k => $v)
            {
                $cs_info[$k]['enroll_id'] = $id;
                unset($cs_info[$k]['id']);
            }
            $res = db('college_admission_score')->insertAll($cs_info);

            echo '更新'.$res.'成功....';
        }
    }


}
