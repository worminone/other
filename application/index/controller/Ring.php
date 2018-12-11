<?php
namespace app\index\controller;

// use app\index\model\User as UserModel;
use think\Db;

class Ring
{
    // 获取应用渠道号
    static function secret_api()
    {
        $sercet_key = '53c4fbd8efb8a597';
    }

    /*
     *    5.1查询栏目资源
     *
     *
     *   a	应用渠道号	String	是	-
     *   id	资源编号	String	是	-
     *   px	页码	String	否	从0开始，默认为0
     *   ps	单页数据数量	String	否	默认为20
     *
     *
     * */

    public function getQcols()
    {
        $id = input('param.id', '104581','int');
        $px = input('param.px', '1','int');
        $ps = input('param.ps','20','int');
        $px= $px-1;
        echo $url = 'http://api.kuyinyun.com/p/q_cols?a=53c4fbd8efb8a597&id='.$id.'&px='.$px.'&ps='.$ps;
        $data = file_get_contents($url);
        $data = @json_decode($data, true);
        echo '<pre>';
        print_r($data);
        dd($data);
        $content = self::curl_api($url,[]);
        dd($content);
    }
    //5.2查询栏目下铃音资源

    public function getQcolres()
    {
        $id = input('param.id', '104579','int');
        $px = input('param.px', '1','int');
        $ps = input('param.ps','20','int');
        $px= $px-1;
        echo $url = 'http://api.kuyinyun.com/p/q_colres?a=53c4fbd8efb8a597&id='.$id.'&px='.$px.'&ps='.$ps;
        $data = file_get_contents($url);
        $data = @json_decode($data, true);
        echo '<pre>';
        print_r($data);
        dd($data);
        $content = self::curl_api($url,[]);
        dd($content);
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
        //字符集【11】
        // $dir = iconv('utf-8', 'gbk', $dir);
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        //目录+文件
        $filename = $dir . (empty($filename) ? '/' . time() . $ext : '/' . $filename);
        //开始捕捉
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        // dd($filename);
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
            //字符集【11】
            // $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.mp3');
            $title = $title . '.mp3';
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
            //字符集【11】
            // $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.mp3');
            $title = $title . '.mp3';
            $this->grabImage($audiourl, $dir, $title);
            echo $val['title'].'.mp3 == 已经更新<br/>';
        }
    }

    //口袋铃声
    public function getRingBoxs(){
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $arr = [
            // 'appid=00000590741&callback=&format=json&hash=9F8C9E03276D5CD41C8EFE2180E184C7&nettype=0&pichigh=100&picwide=100&pid=1540&records=20&service=get_ring_list&spinfocode=00000590741&start=0&timespan=1540827291082&uid=33298543&usercode=01BA98B6CF83F0FA9E88A49258EB6142&version=40'
            '
{"data":{"list":[{"name":"缘起(前世今生版)(《白蛇：缘起》电影推广曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/11/30/33d81f807d264157ac7e8207b34a88bf.jpg?m","ringid":"4916057","iscrbt":0,"synopsis":{"sinfo":"周深-于是万般纠缠 只因当时缘起","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7617b0d955d117a2e90b96dacd7f6489","playtime":48,"islike":0,"likenum":4,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4916057","isvip":"0","songer":"周深","isringing":1,"isbell":1,"ishot":"0","rtime":1543546447000,"issearch":0},{"name":"日暮归途","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/06/e11e24a2ad1c44869a34801b84daa8d0.jpg?m","ringid":"3873471","iscrbt":0,"synopsis":{"sinfo":"HITA-天地虽大却不如 与你一马一剑驰骋川谷","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"15219101b5fd8c7f17d8b9e7f96dcea0","playtime":48,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3873471","isvip":"0","songer":"HITA","isringing":1,"isbell":1,"ishot":"0","rtime":1510072282000,"issearch":0},{"name":"是风动","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/300.jpeg","ringid":"4002896","iscrbt":0,"synopsis":{"sinfo":"银临,河图-青山元不动 白云自去来","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5bb612453ea3c05959e19481f1028556","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4002896","isvip":"0","songer":"银临,河图","isringing":1,"isbell":1,"ishot":"0","rtime":1517502005000,"issearch":0},{"name":"离尘","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/06/e11e24a2ad1c44869a34801b84daa8d0.jpg?m","ringid":"3873469","iscrbt":0,"synopsis":{"sinfo":"严堡丹-也罢也罢 离尘独立 束之高阁","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"15219101b5fd8c7ff8fc111fdb2c0187","playtime":48,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3873469","isvip":"0","songer":"严堡丹","isringing":1,"isbell":1,"ishot":"0","rtime":1510072282000,"issearch":0},{"name":"雁归","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/02/26/da73841f5d0f4724a749566eab11d013.jpg?m","ringid":"4050495","iscrbt":0,"synopsis":{"sinfo":"伦桑-月圆人未圆 雁归人未归","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137bd3dd8e77f54dff8b","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4050495","isvip":"0","songer":"伦桑","isringing":1,"isbell":1,"ishot":"0","rtime":1519748510000,"issearch":0},{"name":"挑兰灯","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/06/e11e24a2ad1c44869a34801b84daa8d0.jpg?m","ringid":"3873466","iscrbt":0,"synopsis":{"sinfo":"Aki阿杰-玉人秉烛挑兰灯 焰火摇曳照玉阙","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"15219101b5fd8c7f3865f64c05fa1b8f","playtime":48,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3873466","isvip":"0","songer":"Aki阿杰","isringing":1,"isbell":1,"ishot":"0","rtime":1510072282000,"issearch":0},{"name":"故梦","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/22.jpeg","ringid":"4215168","iscrbt":0,"synopsis":{"sinfo":"陈怡茵-月色摇晃对影成双 一起诉说地久天长","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d6c4191cabd89fbbd8cfcbb026e6538f","playtime":48,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4215168","isvip":"0","songer":"陈怡茵","isringing":1,"isbell":1,"ishot":"0","rtime":1526632848000,"issearch":0},{"name":"舞龙凤","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/06/e11e24a2ad1c44869a34801b84daa8d0.jpg?m","ringid":"3873470","iscrbt":0,"synopsis":{"sinfo":"奇然-天生我就这么猛 要当就当英雄","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"15219101b5fd8c7fb7924c57336780ae","playtime":48,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3873470","isvip":"0","songer":"奇然","isringing":1,"isbell":1,"ishot":"0","rtime":1510072282000,"issearch":0},{"name":"流光记","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/11/28/db65864a6e4943929cfcb56389fbc97f.jpg?m","ringid":"4907983","iscrbt":0,"synopsis":{"sinfo":"银临-岁月走走停停 边际就是无边无际","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a621bfc3f45e717735e713a735d7cd3b","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4907983","isvip":"0","songer":"银临","isringing":1,"isbell":1,"ishot":"0","rtime":1543427726000,"issearch":0},{"name":"芊芊","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2013/08/25/130711222004401.jpg?m","ringid":"2265207","iscrbt":0,"synopsis":{"sinfo":"回音哥-绝唱一段芊芊 爱无非看谁成茧","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f19771f5bb8a15bcf6cb830c9ec52e21","playtime":48,"islike":0,"likenum":8,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2265207","isvip":"0","songer":"回音哥","isringing":1,"isbell":1,"ishot":"0","rtime":1468217951000,"issearch":0},{"name":"如梦曾经","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/01/31/9fb222cc803347f0afc6b7b683d26309.jpg?m","ringid":"4114974","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"3b77fd5b8c3fc1528e5f3ff06be20e24","playtime":48,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4114974","isvip":"0","songer":"小曲儿","isringing":1,"isbell":1,"ishot":"0","rtime":1523534271000,"issearch":0},{"name":"生旦净末丑","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/04/18/ba5058999e474697ab5e1d11a974d857.jpg?m","ringid":"4130246","iscrbt":0,"synopsis":{"sinfo":"少司命-中华国粹，千年传承","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f21dab2e13fa4036768","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4130246","isvip":"0","songer":"少司命","isringing":1,"isbell":1,"ishot":"0","rtime":1524155173000,"issearch":0},{"name":"昙华愿","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/22/3ed7a31954034bf2b7ba0e790e678079.jpg?m","ringid":"4231350","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f21196a2b0abe7b569c","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4231350","isvip":"0","songer":"少司命","isringing":1,"isbell":1,"ishot":"0","rtime":1527100831000,"issearch":0},{"name":"一爱难求(电视剧《扶摇》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/20/546c654f900846b79e8ccb417c8b9276.jpg?m","ringid":"4308315","iscrbt":0,"synopsis":{"sinfo":"徐佳莹-爱恨交织与你共鸣","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"02a24b7e00d53c87f668db7094339e4f","playtime":48,"islike":0,"likenum":17,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4308315","isvip":"0","songer":"徐佳莹","isringing":1,"isbell":1,"ishot":"0","rtime":1529466021000,"issearch":0},{"name":"琼花房","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/07/02/1611181855139502.jpg?m","ringid":"4780908","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"37caa1a5439d9e686b20acbc303bce0a","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4780908","isvip":"0","songer":"胡碧乔","isringing":1,"isbell":1,"ishot":"0","rtime":1541354218000,"issearch":0},{"name":"西塘","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/07/05/56b8dbed84d54225867883e564343186.jpg?m","ringid":"3845371","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"59868e427019445daaf9af42af42386a","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3845371","isvip":"0","songer":"齐晨","isringing":1,"isbell":1,"ishot":"0","rtime":1506788483000,"issearch":0},{"name":"胭脂香","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2015/11/03/1511030935225205.jpg?m","ringid":"1920949","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d50c4f169cc9d04b5d32e91fe7b3ce76","playtime":48,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1920949","isvip":"0","songer":"鲁士郎,崔子格","isringing":1,"isbell":1,"ishot":"0","rtime":1468155802000,"issearch":0},{"name":"有美人兮","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/21/591b7bcccc1b4419afd587fe1cacf4ae.jpg?m","ringid":"4179289","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"025bc3b771d4f7b026d6028cd5c45325","playtime":48,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4179289","isvip":"0","songer":"音阙诗听,赵方婧,王梓钰","isringing":1,"isbell":1,"ishot":"0","rtime":1525603200000,"issearch":0},{"name":"小巷酒馆","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/02/5312087db8994df7a8c51c9b69b32ad9.jpg?m","ringid":"3860927","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ad1f28ca3d68f218ee86ca5e3b6a448e","playtime":48,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3860927","isvip":"0","songer":"郑国锋,何曼婷","isringing":1,"isbell":1,"ishot":"0","rtime":1508084898000,"issearch":0},{"name":"风筝误","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/28/1609222331299319.jpg?m","ringid":"2377838","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5137839855f5b9f47dab443f4a2f9e73","playtime":48,"islike":0,"likenum":17,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2377838","isvip":"0","songer":"刘珂矣","isringing":1,"isbell":1,"ishot":"0","rtime":1468243835000,"issearch":0}],"list_editer":"彼岸忘川爱荼蘼","list_islike":"0","list_likenum":"1","list_name":"古风精选｜但愿长醉不复醒","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1543/88/68/1543886882778.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1560","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1543/88/69/1543886903341.png","list_content":"谓古风者，可记古事故事，亦可叙豪情柔情；可谈风花雪月，亦可诉悲欢离合；可作诗词歌赋，亦离尘可书锦绣文章…","total_count":20,"list_count":20},"hash":"","code":1,"msg":"","timespan":1544514837092}',
            ];
        foreach ($arr as $v){
            $this->getRingBox($v);
        }
    }
    public function getRingBox($data)
    {
        // echo  $data;
        // $data = $this->curl_api('https://api.ringbox.com.cn/ringsetclientv3/phone.htm',$data, 'get');
        // $data = $this->fileGetContentsPost($data);
        $data = json_decode($data, true);
        // dd($data);
//        dd($data['data']);
//        echo '<pre>';
//        print_r($data['data']);
        $data = $data['data'];
        /* //专题*/
        $dir_name = $data['list_name'];
        $max_img = $data['list_pic'];
        $min_img = $data['list_urlpic'];
//        dd($dir_name);
        $dir = 'G:/music/' . $dir_name;
        $this->grabImage($max_img, $dir, 'dd.jpg');
        $this->grabImage($min_img, $dir, 'xx.jpg');
//        file_put_contents($dir.'/file.txt', $data['list_content'],FILE_APPEND);
//        专题
        foreach ($data['list'] as $key => $value) {
            $url = 'https://ringzm.ringbox.com.cn/ring.ringbox.com.cn/tmp3/';
            $ringid = $value['ringid'];
            $str_id = '000'.$ringid;
            $first_url = substr($str_id, 0, 4);
            $sec_url = substr($str_id, 4, 2);
            $third_url = substr($str_id, 6, 2);
            $url = $url.$first_url.'/'.$sec_url.'/'.$third_url.'/'.$str_id.'.mp3';
//            $url = $value['audiourl'];
            $title = $value['name'].'-'.$value['songer'];
            //字符集【11】
            // $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.mp3');
            $title = $title . '.mp3';
            $this->grabImage($url, $dir, $title);

            echo $value['name'] . '.mp3 == 已经更新<br/>';
        }
    }


    //酷狗铃声
    public function getRingKus(){
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $arr = [
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=434&t=0%202&p=1&pn=40',
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=435&t=0%202&p=1&pn=40',
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=352&t=0%202&p=1&pn=40',
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=370&t=0%202&p=1&pn=80',
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=433&t=0%202&p=1&pn=40',
            // 'http://api.ring.kugou.com/ring/ctgdetails?ctid=432&t=0%202&p=1&pn=40',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=445&t=0%202&p=1&pn=80',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=368&t=0%202&p=1&pn=80',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=412&t=0%202&p=1&pn=80',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=460&t=0%202&p=1&pn=40',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=380&t=0%202&p=1&pn=40',
//            'http://api.ring.kugou.com/ring/ctgdetails?ctid=392&t=0%202&p=1&pn=40',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=144&t=0%202%205&p=1&pn=40',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=462&t=0%202%205&p=1&pn=40',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=462&t=0%202%205&p=1&pn=40'

        ];
        foreach ($arr as $v){
            $this->getRingKu($v);
        }
    }
    function getRingKu($url)
    {
        $content = file_get_contents($url);
        $data = json_decode($content, true);
        $data = $data['response'];

        $dir_name = $data['name'];
        $max_img = $data['image'];
//        $min_img = $data['musicInfo'][14]['image']['small'];
        $min_img = $data['image'];


       // dd($dir_name);
        $dir = 'G:/music/' . $dir_name;
        $this->grabImage($max_img, $dir, 'dd.jpg');
        $this->grabImage($min_img, $dir, 'xx.jpg');
//        file_put_contents($dir.'/file.txt', $data['intro'],FILE_APPEND);
        //        专题
        foreach ($data['musicInfo'] as $key => $value) {
            $url = $value['url'];
//            $url = $value['audiourl'];
            $title = $value['ringName'].'-'.$value['singerName'];
            //字符集【11】
            // $title = iconv("UTF-8", "GB2312//IGNORE", $title . '.mp3');
            $title = $title . '.mp3';
            // dd($title);
            $this->grabImage($url, $dir, $title);

            echo $value['ringName'] . '.mp3 == 已经更新<br/>';
        }
    }

    /*
     * 模拟 api 登录
     * */
    public function fileGetContentsPost($data){
        $url = 'https://api.ringbox.com.cn/ringsetclientv3/phone.htm';
        //url 转化成数组
        $map = explode('&', $data);
        $post = [];
        foreach ($map as $value)
        {
            $arr = explode('=',$value);
            $post[$arr[0]] = $arr[1];
        }
        dd($post);
        //
        $options = array(
            'http'=> array(
            'method'=>'POST',
            'header'=>"Host: api.ringbox.com.cn\r\n"
                ."Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n",
            'content'=> http_build_query($post),
            ),
        );
        $result = file_get_contents($url,false, stream_context_create($options));
        return $result;
    }


    // api请求封装
    public function curl_api($api, $paraArr, $method = 'get', $is_debug = 0)
    {
        $headers = [];

        $ch = curl_init();
        if ($method == 'get') {
            $url = $api . '?' . $paraArr;
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            $paras = http_build_query($paraArr);

            curl_setopt($ch, CURLOPT_URL, $api);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paraArr);
        }

        if ($is_debug) {
            echo $api . '?' . $paras;
            exit();
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);      //是否返回头部信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $data = curl_exec($ch);
    //     $debug = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    //     aa($debug);

        curl_close($ch);
    //    $data = @json_decode($data, true);
        return $data;
    }

}