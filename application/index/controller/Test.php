<?php
namespace app\index\controller;

// use app\index\model\User as UserModel;
use think\cache\driver\Redis;
use think\Db;
use app\index\model\SpecialtyScoreV3;
use app\index\model\CollegeMajor;

class Test
{
	public function getImage()
	{
		ob_clean();           
  
		$realname = "姓名";  
		$schoolname = "学校";    
		$idcard = "身份证号";   
        $img_url = __ROOT__.'./static/img/certification.png';
        dd($img_url);
		$image = imagecreatefrompng('http://img.cnwav.com/images/logo.gif');           // 证书模版图片文件的路径
		$red = imagecolorallocate($image,00,00,00);                 // 字体颜色  
		  
		// imageTTFText("Image", "Font Size", "Rotate Text", "Left Position","Top Position", "Font Color", "Font Name", "Text To Print");  
		imageTTFText($image, 50, 0, 1110, 1025, $red, '字体路径',$realname);  
		imageTTFText($image, 50, 0, 1000, 1023, $red, '字体路径', $schoolname);  
		imageTTFText($image, 55, 0, 900, 810, $red, '字体路径（/usr/share/fonts/truetype/ttf-dejavu/simhei.ttf）', $idcard);  
		  
		 /* If you want to display the file in browser */  
		header('Content-type: image/png;');  
		ImagePng($image);  
		imagedestroy($image);  
		  
		  
		/* if you want to save the file in the web server */  
		$filename = 'certificate_aadarsh.png';  
		ImagePng($image, $filename);  
		imagedestroy($image);  
		  
		  
		/* If you want the user to download the file */  
		$filename = 'certificate_aadarsh.png';  
		ImagePng($image,$filename);  
		header('Pragma: public');  
		header('Cache-Control: public, no-cache');  
		header('Content-Type: application/octet-stream');  
		header('Content-Length: ' . filesize($filename));  
		header('Content-Disposition: attachment; filename="' .  
		 basename($filename) . '"');  
		header('Content-Transfer-Encoding: binary');  
		readfile($filename);  
		imagedestroy($image);  
	}

	public function plan()
    {
        $list = Db::name('college_enrollment_plan')->where(['college_id'=>998])->select();
        foreach ($list as $key=>$value) {
            $map = explode(',',$value['science']);
            if(count($map) >=2) {
                $data = '';
                foreach ($map as $k=>$v) {
                    $data[$k] = $value;
                    $data[$k]['science'] = $v;
                    unset($data[$k]['id']);
                }
//                dd($data);
                if(!empty($data)) {
                    Db::name('college_enrollment_plan')->insertAll($data);
                    Db::name('college_enrollment_plan')->where(['id'=>$value['id']])->delete();
                }
            }
        }
        echo 'chengg';
    }

    public function setScore()
    {
        set_time_limit(0);
        $where['id'] = ['in','468,   473,   475,   479,   487,   488,   507,   547,   566,   586,   594,   612,   619,   632,   636,   643,   649,   650,   653,   655,   663,   665,   670,   686,   692,   696,   711,   724,   725,   730,   780,   781,   794,   802,   809,   817,   824,   846,   855,   864,   878,   882,   893,   894,   895,   897,   921,   925,   928,   933,   934,   935,   949,   958,   960,   965,   970,   971,   978,   993,   995,  1005,  1014,  1018,  1071,  1079,  1084,  1100,  1103,  1105,  1115,  1117,  1164,  1179,  1181,  1182,  1184,  1200,  1207,  1213,  1219,  1229,  1234,  1237,  1260,  1267,  7223,  7450,  7557,  7662, 15462, 16028, 16072, 16104, 16114, 16117, 16122, 16124, 16161, 16165, 16167, 16169, 16171, 16176, 16182, 16197, 16199, 16211, 16273, 16297, 16316, 16331, 16340, 16352, 16373, 16394, 16444, 16475, 16499, 16511, 16513, 16525, 16536, 16540, 47311, 47462, 47463, 58713, 58839, 58869, 60214, 60231, 60250, 60259, 60269, 60274, 60289, 60295,111876,111884,111889,111898,111905,111912,111913,111926,111931,111937,111939,111947,111969,111996,112021,112031,112033,112036,112042,112052,112058,112070,112080,112085,112094,112098,112108,112111,112119,112121,112136,112138,112139,112141,112156,112178,112197,112210,112211,112220,112222,112225,112239,112246,112256,112303,112306,112322,112332,112334,112335,112342,112345,112357,112364,112376,112382,112390,112397,112419,112437,112446,112447,112455,112461,112466,112484,112500,112502,112535,112536,112541,112548,112550,112566,112573,112576,112585,112594,112604,112606,112611,112617,112623,112652,11265,'];

        $list = Db::name('specialty_score_v3')->where($where)->limit(20000)->select();
        $data = [];
        $SpecialtyScoreV3 = new SpecialtyScoreV3;
        foreach ($list as $key =>$value) {
            if(strpos($value['shortname'], '（') !== false) {
                $start = mb_strpos($value['shortname'], '（');
                $end = mb_strpos($value['shortname'], '）');
                $direction = mb_substr($value['shortname'], $start+1, $end-$start-1, 'utf-8');
                $majorname = mb_substr($value['shortname'], 0, $start, 'utf-8');
            } else {
                $majorname = $value['shortname'];
                $direction = '';
            }
            $data[$key]= $value;
            $data[$key]['direction'] = $direction;
            $data[$key]['majorname'] = $majorname;
            $data[$key]['status'] = 1;
        }
        $result = $SpecialtyScoreV3->saveAll($data,true);
        //反馈执行信息
        echo $result ? '更新成功!<br />' : '更新失败~~<br />' ;
    }

    public function copyStudentType()
    {
        $where['studenttype'] = ['in',['体育（文）','艺术（文）']];
        $list = Db::name('specialty_score_v3')->where($where)->select();
        $data = [];
        foreach ($list as $key=>$value)
        {
            $data[$key] = $value;
            if($value['studenttype'] =='体育（文）') {
                $data[$key]['studenttype'] = '体育（理）';
            }
            if($value['studenttype'] =='艺术（文）') {
                $data[$key]['studenttype'] = '艺术（理）';
            }
            unset($data[$key]['id']);
        }
        $data = array_values($data);
//        $res = Db::name('specialty_score_v3')->insertAll($data);
//        echo $res;
    }


    public function importScore()
    {
        $ss_where['status'] = 0;
//        $ss_where['id'] = ['<=', '1000'];
        $ss_where['studenttype'] = ['not in', ['体育类','艺术类']];
        $list = Db::name('specialty_score_v3')->where($ss_where)->limit(500)->select();
        echo Db::name('specialty_score_v3')->getLastSql();
//        dd($list);

        foreach ($list as $key => $value) {
            $info = $infos = $a_info = '';
            $where['region_name'] = ['like',$value['localprovince'].'%'];
            $where['parent_id'] = 0;
            $c_info = Db::name('College')->where(['title'=>$value['schoolname']])->find();
//            $m_info = Db::name('MajorInfo')->where(['majorName'=>$value['majorname']])->find();
            $m_where['majorTypeName'] = $value['majorname'];
            $m_where['level'] = ['not in', 1];
            $t_info = Db::name('MajorType')->where($m_where)->find();
//            dd($t_info);
            if(empty($t_info)) {
                Db::name('specialty_score_v3')->where(['id'=>$value['id']])->update(['status'=>2]);
                continue;
            }
//            echo Db::name('MajorType')->getLastSql();
            $r_info = Db::name('Region')->where($where)->find();
//             dd($t_info);
//            if($m_info) {
                $info['college_id'] = $c_info['college_id'];
                if($t_info['level'] == 2) {
                    $info['majorName'] = $t_info['majorTypeName'];
                    $info['majorNumber'] = $t_info['majorTypeNumber'];
                } else {
                    $info['majorName'] = $t_info['majorTypeName'];
                    $info['majorNumber'] = $t_info['majorTypeNumber'];
                    $mt_info = Db::name('MajorType')->where(['majorTypeNumber'=>$t_info['majorTopTypeNumber']])->find();
                    $t_info['majorTypeNumber'] = $mt_info['majorTypeNumber'];
                    $t_info['majorTypeName'] = $mt_info['majorTypeName'];
                }
                $info['majorTypeNumber'] = $t_info['majorTypeNumber'];
                $info['majorTypeName'] = $t_info['majorTypeName'];
                $info['type_id'] = $t_info['type_id'];
//                $info['major_id'] = $m_info['major_id'];
                $info['department_id'] = 0;
                $info['department_name'] = '';
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

                $e_info = Db::name('CollegeMajorEnroll')->where($s_where)->find();
//                dd($info);
                if($e_info) {
                    $id = $e_info['id'];
                } else {
                    $id = Db::name('CollegeMajorEnroll')->insertGetId($info);
                }

                $infos['enroll_id'] = $id;
                $infos['science'] = $value['studenttype'];

                $infos['max'] =$value['max'];
                $infos['min'] =$value['min'];
                $infos['avg'] =$value['avg'];
                if($value['studenttype'])
                $a_info = Db::name('CollegeAdmissionScore')->where(['enroll_id'=>$id, 'science'=>$value['studenttype']])->find();
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
                Db::name('specialty_score_v3')->where(['id'=>$value['id']])->update($data);
//            }else{
//                $data['status'] = 2;
//                Db::name('specialty_score_v3')->where(['id'=>$value['id']])->update($data);
//                continue;
//            }
            echo '添加ID'.$id.'<br>';
        }
        $count = Db::name('specialty_score_v3')->where(['status'=>0])->count();
        echo '新增结束,剩下数量'.$count;

    }

    public function saveCollegeMajorDirection()
    {
        $sql = 'SELECT COUNT(*) AS num, majorNumber,majorTypeNumber,majorName,majorTypeName,province_id,college_id,batch,direction FROM  `dd_college_major_enroll`WHERE direction !="" 
          GROUP BY majorNumber,majorTypeNumber,majorName,majorTypeName,province_id,college_id,batch';
        $list = Db::query($sql);
//        dd($list);
        $data= [];
        foreach ( $list as $key => $value)
        {
              $data[$value['majorNumber'].'_'.$value['majorTypeNumber'].'_'.$value['majorName'].'_'.$value['majorTypeName'].'_'.$value['college_id']][] = $value['direction'];

        }
        $aa = $update_data = '';
        foreach ($data as $k =>$v) {
            $aa[$k]['direction'] = implode(',', array_unique($v));
            $map =  explode('_', $k);
            $where['majorNumber'] = $map['0'];
            $where['majorTypeNumber'] = $map['1'];
            $where['majorName'] = $map['2'];
            $where['majorTypeName'] = $map['3'];
            $where['college_id'] = $map['4'];
            $c_info = Db::name('college_major')->where($where)->find();
            if($c_info) {
                echo Db::name('college_major')->getLastSql();
                $update_data[$k]['id'] = $c_info['id'];
                $update_data[$k]['direction'] = $aa[$k]['direction'];
//                Db::name('college_major')->where($where)->update(['direction'=>$aa[$k]['direction']]);
            }
        }

        $CollegeMajor = new CollegeMajor;
        $update_data = array_values($update_data);
//        dd($aa,$update_data);
        $result = $CollegeMajor->saveAll($update_data,true);
//        echo $CollegeMajor->getLastSql();
//        dd($result);
        echo $result ? '更新成功!<br />' : '更新失败~~<br />' ;
    }

    public function getYearList()
    {
        $sql = 'select college_id,enrollmentYear from `dd_college_major_enroll` where college_id is not null group by college_id,enrollmentYear ';
//        $sql = 'select college_id,enrollmentYear from `dd_college_score` where college_id is not null group by college_id,enrollmentYear ';
        $list = Db::query($sql);
        $data= [];
        $college_info = Db::name('college')->column('title', 'college_id');
        foreach ( $list as $key=>$value)
        {
            $where['college_id'] = $value['college_id'];
            $where['year'] = $value['enrollmentYear'];
            $where['status'] = 2;
//            $college_info = Db::name('college')->where(['college_id'=>$value['college_id']])->find();
            $y_info = Db::name('year')->where($where)->find();
            if(!$y_info) {
                $data[$key]['college_id'] = $value['college_id'];
                $data[$key]['year'] = $value['enrollmentYear'];
                $data[$key]['title'] = $college_info[$value['college_id']].$value['enrollmentYear'].'录取分数';
                $data[$key]['status'] = 2;
                $data[$key]['add_time'] = time();
            }
        }

        $data = array_values($data);
//        echo '<pre>';
//        print_r($data);
//        dd();
        $res = Db::name('year')->insertAll($data);
        echo $res ? '更新成功!<br />' : '更新失败~~<br />' ;
    }

    public function getBatchInsert()
    {
        ini_set('memory_limit','3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $arr = [
            '本科批（农村专项）',
            '本科一批（农村专项）',
            '本科二批（农村专项）',
            '南疆单列计划'
        ];
        $map = [];
        $where['college_id'] = ['<=', '3000'];
        $list = Db::name('college')->where($where)->select();
        $j=0;
        foreach ($list as $key=>$value)
        {
            for ($i=0; $i< count($arr); $i++)
            {
                $map[$j]['college_id'] = $value['college_id'];
                $map[$j]['batch'] = $arr[$i];
                Db::name('college_batch')->insert($map[$j]);
//                $res = Db::name('college_batch')->where($map[$j])->find();
//                if($res)
//                {
//                    unset($map[$j]);
//                    var_dump($map[$j]);
//                }
                $j++;
            }

        }
//        $map = array_values($map);
//        $num =Db::name('college_batch')->insertAll($map);
//        echo $num;
    }


    public function implodeTopic()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $list = Db::name('admission_info')->select();
        $count = Db::name('admission_info')->count();
        $lists = [];
        foreach ( $list as $key => $value)
        {
            $c_where['title'] = $value['college'];
            $info = Db::name('College')->where($c_where)->find();
            if($info) {
                $list1['college_id'] = $info['college_id'];
                $list1['plan_name'] =  $value['college'].$value['year1'].'年招生简章';
                $list1['enrollmentYear'] =  $value['year1'];
                $list1['content'] =  $value['info1'];
                $list1['remarks'] =  $value['remark'];
                $list1['create_time'] = time();
                $list1['update_time'] = time();
                $list2['college_id'] = $info['college_id'];
                $list2['plan_name'] =  $value['college'].$value['year2'].'年招生简章';
                $list2['enrollmentYear'] =  $value['year2'];
                $list2['content'] =  $value['info2'];
                $list2['remarks'] =  $value['remark'];
                $list2['create_time'] = time();
                $list2['update_time'] = time();
                Db::name('college_topic')->insert($list1);
                Db::name('college_topic')->insert($list2);

            }
        }
//        dd(array_values($lists));
//        $lists = array_values($lists);
//        echo  $res = Db::name('college_topic')->insertAll($lists);

        echo '新增成功';

    }

    public function implodeTopic2()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $list = Db::name('admission_content')->select();
        foreach ( $list as $key => $value)
        {
            $c_where['title'] = $value['college'];
            $info = Db::name('College')->where($c_where)->find();
            if($info) {
                $list['college_id'] = $info['college_id'];
                $list['plan_name'] = $value['college'] . $value['ad_year'] . '年招生简章';
                $list['enrollmentYear'] = $value['ad_year'];
                $list['content'] = $value['content'];
                $list['create_time'] = time();
                $list['update_time'] = time();
                Db::name('college_topic')->insert($list);
            }
        }
        echo '新增成功';

    }

    public function getCollegeScore()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $where['status'] = 0;
        $r_where['parent_id'] = 0;
        $r_list = Db::name('region')->where($r_where)->select();
        $rr_list = [];

        foreach ($r_list as $k =>$v)
        {
            $rn = str_replace('省','',$v['region_name']);
            $rr_list[$rn] = $v['region_id'];
        }
        $list = Db::name('college_score_bak')->where($where)->select();
        foreach ($list as $key => $value)
        {
            $a_where['title'] = $value['collegeName'];
            $info = Db::name('College')->where($a_where)->find();
            if($info) {
                $c_where['college_id'] = $info['college_id'];
                $c_where['province_id'] = $rr_list[$value['province']];
                $c_where['science'] = $value['science'];
                $c_where['enrollmentBatch'] = $value['enrollmentBatch'];
                $c_where['enrollmentYear'] = $value['enrollmentYear'];
                $c_info = Db::name('college_score')->where($c_where)->find();
                if(!$c_info) {
                    $data[$key]['college_id'] = $info['college_id'];
                    $data[$key]['collegeCode'] = $info['collegeCode'];
                    $data[$key]['collegeName'] = $value['collegeName'];
                    $data[$key]['province_id'] = $rr_list[$value['province']];
                    $data[$key]['province'] = $value['province'];
                    $data[$key]['science'] = $value['science'];
                    $data[$key]['max'] = $value['max'];
                    $data[$key]['min'] = $value['min'];
                    $data[$key]['avg'] = $value['avg'];
                    $data[$key]['enrollmentBatch'] = $value['enrollmentBatch'];
                    $data[$key]['enrollmentYear'] = $value['enrollmentYear'];
                    $data[$key]['shengkong'] = $value['shengkong'];
                    Db::name('college_score')->insert($data[$key]);
                    Db::name('college_score_bak')->where(['id'=>$value['id']])->update(['status'=>1]);

                }
            }
        }

        echo '更新成功';

    }

    public function getRankScore()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $r_where['parent_id'] = 0;
        $r_list = Db::name('region')->where($r_where)->select();
        $rr_list = [];

        foreach ($r_list as $k =>$v)
        {
            $rn = str_replace('省','',$v['region_name']);
            $rr_list[$rn] = $v['region_id'];
        }
        $list = Db::name('score_baks')->select();
        foreach ($list as $key => $value)
        {
            $c_where['province_id'] = $rr_list[$value['province']];
            Db::name('score_baks')->where(['id'=>$value['id']])->update($c_where);
        }

        echo '更新成功';

    }


    public function insertRank()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $list = Db::name('score_baks')->select();
        foreach ($list as $key=>$value) {
            unset($value['id']);
            Db::name('sci_rank')->insert($value);
        }
        echo '更新成功';
    }


    public function getRankSciScore()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $r_where['parent_id'] = 0;
        $r_list = Db::name('region')->where($r_where)->select();
        $rr_list = [];

        foreach ($r_list as $k =>$v)
        {
            $rn = str_replace('省','',$v['region_name']);
            $rr_list[$rn] = $v['region_id'];
        }
        $list = Db::name('art_rank')->select();
        foreach ($list as $key => $value)
        {
            $c_where['province_id'] = $rr_list[$value['province']];
            Db::name('art_rank')->where(['id'=>$value['id']])->update($c_where);
        }

        echo '更新成功';

    }

    public function getRankSciss()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
//        $where['province_id'] = 2095;
        $group_list = Db::name('sci_rank')->field('province_id,year')->group('province_id')->select();
        foreach ($group_list as $k => $v) {
//            if ($v['province_id'] == 2059) {
                $where['province_id'] = $v['province_id'];
                $where['year'] = $v['year'];
                $list = Db::name('sci_rank')->where($where)->order('score desc')->select();
                foreach ($list as $key => $value) {
                    $s_where['province_id'] = $value['province_id'];
                    $s_where['year'] = $value['year'];
                    $s_where['score'] = ['>', $value['score']];
                    $s_info = Db::name('sci_rank')->where($s_where)->order('score asc')->find();
                    if ($s_info) {
                        $data['num'] = $value['rank'] - $s_info['rank'];
                    } else {
                        $data['num'] = $value['rank'];
                    }
                    Db::name('sci_rank')->where(['id' => $value['id']])->update($data);

                }
//            }
        }
        echo '更新成功';


    }

    public function getRankArt()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $group_list = Db::name('art_rank')->field('province_id,year')->group('province_id')->select();
        foreach ($group_list as $k => $v) {
//            if ($v['province_id'] == 2059) {
            $where['province_id'] = $v['province_id'];
            $where['year'] = $v['year'];
            $list = Db::name('art_rank')->where($where)->order('score desc')->select();
            foreach ($list as $key => $value) {
                $s_where['province_id'] = $value['province_id'];
                $s_where['year'] = $value['year'];
                $s_where['score'] = ['>', $value['score']];
                $s_info = Db::name('art_rank')->where($s_where)->order('score asc')->find();
                if ($s_info) {
                    $data['num'] = $value['rank'] - $s_info['rank'];
                } else {
                    $data['num'] = $value['rank'];
                }
                Db::name('art_rank')->where(['id' => $value['id']])->update($data);

            }
//            }
        }

        echo '更新成功';


    }


    public function insertsRank()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $list = Db::name('art_rank')->select();
        foreach ($list as $key=>$value) {
            unset($value['id']);
            Db::name('sci_rank')->insert($value);
        }
        echo '更新成功';
    }

    public function saveArticle()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $sql = 'select * from dd_term_article as a left join dd_article as b on a.article_id = b.id
 where a.term_type=1 and b.status=1 and a.status=1 and a.category_id in (176,174,229)';
        $data  =Db::query($sql);

        $datas = [];
        foreach ($data as $key => $value) {
            $datas['category_id'] = $value['category_id'];
            $datas['title'] = $value['title'];
            $datas['pic'] = $value['cover'];
            $datas['head_img'] = $value['head_img'];
            $datas['title'] = $value['title'];
            $datas['summary'] = $value['summary'];
            $datas['content'] = $value['content'];
            $datas['view_count'] = $value['view_count'];
            $datas['created_at'] = $value['create_time'];
            $datas['updated_at'] = $value['create_time'];
            Db::name('articles')->insert($datas);
        }

        dd('502');
        dd($data);

    }

    public function getRegion()
    {
        $data = json_decode($data, true);
        $map = [];
        $i = 0;
        foreach ($data['result'] as $key=>$value)
        {
            foreach ($value as $k =>$v){
                $one = substr($v['id'],0,2);
                $two = substr($v['id'],2,2);
                $three = substr($v['id'],4,2);
                if($two == '00' && $three == '00') {
                    $map[$i]['pid'] = 0;
                }

                if($two != '00' && $three == '00') {
                    $map[$i]['pid'] = $one.'0000';
                }

                if($two != '00' && $three !== '00') {
                    $map[$i]['pid'] = $one.$two.'00';
                }
                $map[$i]['level'] = $key+1;
                $map[$i]['name'] = $v['fullname'];
                $map[$i]['id'] = $v['id'];
                $i++;
            }
        }
        Db::name('region')->insertAll($map);
        dd($map);
    }

    //替换 http 到 https
    public function httptohttps()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $database = input('param.database');
        $sql = "SELECT table_name,table_comment FROM information_schema.tables WHERE TABLE_SCHEMA = '".$database."'";
        $db_data = Db::query($sql);
        foreach ($db_data as $key => $value)
        {
            $table = substr($value['table_name'], 3);
            $sqls = "select * from ".$value['table_name'];
            $table_data = Db::query($sqls);
            foreach ($table_data as $k => $v) {
                $v = $this->nullToStr($v);
                Db::name($table)->update($v);
            }
        }
        echo $database.'更新成功';
    }

    // 递归处理 表字段null 替换成空
    public function nullToStr($arr)
    {
        if(is_array($arr)) {
            foreach ($arr as $k=>&$v){
                if(is_null($v)) {
                    $arr[$k] = '';
                }
                if(is_array($v)) {
                    $arr[$k] = static::nullToStr($v);
                }

                if(is_string($v)){
                    $v = str_replace("http://image.zgxyzx.net","https://image.zgxyzx.net",$v);
                    $v = str_replace("http://image.dadaodata.com","https://image.dadaodata.com",$v);
                    $v = str_replace("http://www.1zy.me","https://www.1zy.me",$v);
                }
            }
        }
        return $arr;
    }
}