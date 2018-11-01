<?php
namespace app\index\controller;

// use app\index\model\User as UserModel;
use think\cache\driver\Redis;
use think\Db;

class User
{
	public function add() {
		$user = new UserModel();
        $user->name  = 'wmin';
        $user->email = 'wmin@gmail.com';
        $user->age   = '24';
        $user->sex   = 1;
    
        if($user->save($user)) {
            return  "新增成功";
        } else {
            return '新增失败';
        }
	}

    public function setRedis() {
        // $redis = new Redis();
        $data = DB::name("User")->where('id','in', '1,2,5')->select();
        echo DB::name("User")->_sql();
        die();
       // echo DB::name("User")->getLastSql();
        //$key = "user_".$data['id'];
        // $data = json_encode($data);
         // dd($data);
        // dd($redis);
        if ($redis->set('user',$data)) {
            $test = $redis->get('user');
            dump($test);
            echo '数据成功提交到redis';
        }
        // $test = $redis->get('test');
        // dump($test);
       
    }

    public function  getRedis() {
        $redis = new Redis();
        // $id = $this->param('id');
        $id  = 1;
        $key = "user_".$id;
        $test = $redis->get('user');
        // 清楚对应的redis数据
      //  $redis->rm($key);
        //清楚所有的redis 数据
        // $redis->clear();

       // DB::name('User')->isupdate()->save();
        dump($test);


    }

    public function  rmRedis() {

    }

    //可用于后台审核列表()
    public function addRedisList($key, $info = "") {
        $redis = new redis();
        $list = $redis->get($key);
        if($info != "") {
            $list[] = $info;
        }
        $list = $redis->set($key, $list);
        $list = $redis->get($key);
        dump($list);

    }
    //确定审核
    public function addRedisDbInfo($key, $id) {
        $redis = new redis();
        $list = $redis->get($key);
        // dump($list);
        foreach ($list as $key => $value) {
            // dump($value);
            if($value['id'] == $id) {
                //这一步 到时候操作数据库;
                // $result = DB::name("User")->isupdate()->save($value);
                // if($result) {
                //     unset($list[$key]);
                // }
                unset($list[$key]);
               // $redis->rm($key);
            }
        }
        // $list = $this->array_sort($list, 'add_time');
        // dump($list);
        $list = $redis->set($key, $list);
        dump($list);
    }

    //多维数组数组排序
    public function array_sort($arr, $keys, $type = 'desc') {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

    public function getCollege() {
       $field = 'a as title, c as school_level ,d as province ,e as city,g schools_type,h as collegeCode,i as collegeNature,j as collegesAndUniversities';
       $s_list = DB::name('CollegeAdd')->field($field)->select();
       DB::name('College')->insertAll($s_list);
    }

    public function getCollegeInfo()
    {
        set_time_limit(0);
        $list = DB::name('CollegeData')->select();
        foreach ($list as $key => $value) {
            $data['school_characteristic'] = $value['empl_rate'];
            $data['address'] = $value['school_addr'];
            $data['school_profiles'] = $value['content'];
            $data['toll_standard'] = $value['tuition_info'];
            // $data['principal'] = $value[''];
            $data['master_num'] = $value['master_num'];
            $data['doctor_num'] = $value['doct_num'];
            $data['academician_num'] = $value['acade_num'];
            $data['website'] = $value['school_url'];
            $data['import_num'] = $value['ksub_num'];
            $data['boy_num'] = $value['boy_ratio'];
            $data['girl_num'] = $value['girl_ratio'];
            $data['students_source'] = $value['studentsSource'];
            $data['email'] = $value['admiss_email'];
            $data['telephone'] = $value['admiss_tel'];
            // dd($data);
            DB::name('College')->where(['college_id'=>$value['college_id']])->update($data);
        }
        echo '11';

    }

    public function testJsonp()
    {
        //获取回调函数名  
          
        $arr=array(  
                    array(  
                        'name'=>'zhangsan',  
                        'sex' =>'man',  
                        'age' =>18,  
                        ),  
                    array(  
                        'name'=>'lisi',  
                        'sex' =>'women',  
                        'age' =>20,  
                        ),  
                    array(  
                        'name'=>'wangwu',  
                        'sex' =>'man',  
                        'age' =>19,  
                        ),  
                );  
          
          
        //输出jsonp格式的数据  
        return $arr;
    }


    public function getArticle()
    {
        set_time_limit(0);
        $where['cate_id'] = ['in', '10002,10003'];
        $list = DB::name('ArticleAdd')->where($where)->select();
        // dd($list);
        foreach ($list as $key => $value) {
            $param['title'] = $value['title'];
            $param['content'] = $value['content'];
            $param['create_time'] = date('Y-m-d H:i:s', $value['add_time']);
            $param['publish_time'] = $value['add_time'];
            $param['summary'] = $value['intro'];
            $id = DB::name('Article')->insertGetId($param);
            $param1['article_id'] = $id;
            $param1['category_id'] = $value['cate_id'];
            $param1['term_type'] = 3;
            $param1['publish_time'] = $value['add_time'];
            $param1['cover'] = 'http://gz.zgxyzx.net'.$value['pic_url'];
            $ids = DB::name('TermArticle')->insertGetId($param1);
        }

        echo '1111';
        // UPDATE `dd_term_article` SET category_id= 186 WHERE category_id ='10003'; //行业
        // UPDATE `dd_term_article` SET category_id= 185 WHERE category_id ='10002' //公司
    }

    public function getJobType()
    {
        $list = DB::name('JobType')->select();
        foreach ($list as $key => $value) {
            if($value['cate_level'] == 0) {
                $l_data['industry_name'] = $value['cate_name'];
                DB::name('IndustryInfo')->insert($l_data);
            } else {
                $t_data['industry_id'] = $value['cate_level'];
                $t_data['occupationTypeName'] = $value['cate_name'];
                DB::name('OccupationType')->insert($t_data);
            }
        }
    }

    public function getJobInfo()
    {
        $list = DB::name('JobInfo')->select();
        foreach ($list as $key => $value) {
           $info = DB::name('OccupationType')->where(['occupationTypeName'=>$value['cate_name']])->find();
           // $data['occupation_name'] = $value['cate_name'];
           $data[$key]['type_id'] = $info['type_id'];
           $data[$key]['occupation_name'] = $value['job_name'];
           $data[$key]['occupation_describe'] = $value['is_what'];
           $data[$key]['job_content'] = $value['do_what'];
           $data[$key]['about_skill'] = $value['who'];
           $data[$key]['skill_approach'] = $value['how'];
           DB::name('OccupationInfo')->insert($data[$key]);
           // $data[''] = $value['tips'];
        }
        dd($data);
        $data = array_values($data);
        $num = DB::name('OccupationInfo')->insertAll($data);
        echo '完成'.$num.'条！';
    }
    public function getJobToJob()
    {
        $list = DB::name("job_relate_jobs")->field('MAX(job_name) as job_name,GROUP_CONCAT(relate_job_name) as name')->group('job_id')->select();
        foreach ($list as $key => $value) {
            DB::name('OccupationInfo')->where(['occupation_name'=>$value['job_name']])->update(['employment_forward'=>$value['name']]);
        }
    }


    public function getReplacetitle()
    {
        $where['shortname'] = ['like', '%（%'];
        $list = DB::name('specialty_score_v2')->where($where)->limit(1000)->select();
        // dd($list);
        foreach ($list as $key => $value) {
            $shortname = preg_replace("/\（.*/", '', $value['shortname']);
            $res = DB::name('specialty_score_v2')->where(['id'=>$value['id']])->update(['shortname'=>$shortname]);
            echo DB::name('specialty_score_v2')->getLastSql();
            echo $res.'<br/>';
        }
        echo '123';
    }

    //导入专业分数
    public function insertMajor()
    {
        set_time_limit(0);
        $start_time = microtime(true);
        $limit = 2000;
        $page = 1;
        $list = Db::name('specialty_score_v2')
            ->limit($limit * ($page - 1), $limit)
            ->where(['status'=>0])
            ->order('id desc')
            ->select();
            // dd($list);
        foreach ($list as $key => $value) {
            $where['region_name'] = ['like',$value['localprovince'].'%'];
            $where['parent_id'] = 0;
            $c_info = Db::name('College')->where(['title'=>$value['schoolname']])->find();
            $m_info = Db::name('MajorInfo')->where(['majorName'=>$value['shortname']])->find();
            $t_info = Db::name('MajorType')->where(['majorTypeNumber'=>$m_info['majorTypeNumber']])->find();
            $r_info = Db::name('Region')->where($where)->find();
            // dd($r_info);
            if($m_info) {
                $info['college_id'] = $c_info['college_id'];
                $info['majorName'] = $m_info['majorName'];
                $info['majorNumber'] = $m_info['majorNumber'];

                $info['type_id'] = $m_info['type_id'];
                $info['major_id'] = $m_info['major_id'];
                $info['department_id'] = 0;
                $info['department_name'] = '';
                $info['majorTypeNumber'] = $m_info['majorTypeNumber'];
                $info['province_id'] = $r_info['region_id'];
                $info['province'] = $r_info['region_name'];
                $info['enrollmentYear'] = $value['year'];
                $info['batch'] = $value['batch'];
                $info['majorTypeName'] = $t_info['majorTypeName'];
                $s_where['enrollmentYear'] = $info['enrollmentYear'];
                $s_where['college_id'] = $info['college_id'];
                $s_where['majorNumber'] = $info['majorNumber'];
                $s_where['province_id'] = $info['province_id'];
                // dd($info);
                $e_info = Db::name('CollegeMajorEnroll')->where($s_where)->find();
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

                $id = Db::name('CollegeAdmissionScore')->insertGetId($infos);
                $data['status'] = 1;
                Db::name('specialty_score_v2')->where(['id'=>$value['id']])->update($data);
           } else {
                $data['status'] = 2;
                Db::name('specialty_score_v2')->where(['id'=>$value['id']])->update($data);
                continue;
           }
            echo '添加ID'.$id.'<br>';
        }
        $end_time = microtime(true);
        echo '脚本执行时间为：'.($end_time-$start_time).' s';
        // dd($info);
    }

    public function getUnique()
    {
        $s_sql = 'SELECT MAX(e.id) AS id, COUNT(*) AS num FROM `dd_college_major_enroll` AS e LEFT JOIN `dd_college_admission_score` AS s ON  e.id = s.enroll_id where college_id !=998 GROUP BY college_id,province_id,majorNumber,batch,enrollmentYear,science,department_id HAVING num >= 2';
        $s_list = Db::query($s_sql);
        $ids = [];
        foreach ($s_list as $key => $value) {
            $ids[] = $value['id'];
        }
        DB::name('college_admission_score')->where(['enroll_id','in',$ids])->delete();
        DB::name('college_major_enroll')->where(['id','in',$ids])->delete();
    }

    public function getRank()
    {
        $list = Db::name('sch_17')->select();
        foreach ($list as $key => $value) {
            $info = Db::name('college')->where(['title'=>$value['sch_name']])->find();
            if($info) {
                Db::name('college')->where(['college_id'=>$info['college_id']])->update(['rank'=>$value['sch_rank_index']]);
            }
        }
    }

    public function getlevel()
    {
        $list = Db::name('college_a')->select();
        dd($list);
        foreach ($list as $key => $value) {
            $info = Db::name('college')->where(['title'=>$value['title']])->find();
            if($info) {
                Db::name('college')->where(['college_id'=>$info['college_id']])->update(['school_level'=>$value['school_level']]);
            }
        }
    }

    public function getYearList()
    {
        $sql = 'SELECT college_id,enrollmentYear FROM `dd_college_major_enroll_20180103` GROUP BY college_id,enrollmentYear ';
        $list = Db::query($sql);
        $data = [];
        foreach ($list as $key => $value) {
            $info = Db::name('Year')->where(['college_id'=>$value['college_id'], 'year'=>$value['enrollmentYear'],'status'=>2])->find();
            if(!$info) {
                $c_info = Db::name('College')->where(['college_id'=>$value['college_id']])->find();
                $data[$key]['college_id'] = $value['college_id'];
                $data[$key]['year'] = $value['enrollmentYear'];
                $data[$key]['title'] = $c_info['title'].$value['enrollmentYear'].'录取分数';
                $data[$key]['status'] = 2;
                $data[$key]['add_time'] = time();
            }
        }
        $data = array_values($data);
        $num = Db::name('Year')->insertAll($data);
        echo '新增'.$num;
    }

    public function getYearV3List()
    {
        $sql = 'SELECT college_id,enrollmentYear FROM `dd_college_Score` GROUP BY college_id,enrollmentYear limit 0,16000';
        $list = Db::query($sql);
        $data = [];
        foreach ($list as $key => $value) {
            $info = Db::name('Year')->where(['college_id'=>$value['college_id'], 'year'=>$value['enrollmentYear'],'status'=>3])->find();
            if(!$info) {
                $c_info = Db::name('College')->where(['college_id'=>$value['college_id']])->find();
                $data[$key]['college_id'] = $value['college_id'];
                $data[$key]['year'] = $value['enrollmentYear'];
                $data[$key]['title'] = $c_info['title'].$value['enrollmentYear'].'院校录取分数';
                $data[$key]['status'] = 3;
                $data[$key]['add_time'] = time();
            }
        }
        $data = array_values($data);
        $num = Db::name('Year')->insertAll($data);
        echo '新增'.$num;
    }



}