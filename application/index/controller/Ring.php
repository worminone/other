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
{"data":{"list":[{"name":"眉间雪","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/120.jpeg","ringid":"4050425","iscrbt":0,"synopsis":{"sinfo":"卡修Rui-师傅，你在等谁？ 我谁也没等，谁也不会来","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137b904b5860965ed6ac","playtime":264,"islike":0,"likenum":5,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4050425","isvip":"0","songer":"卡修Rui","isringing":1,"isbell":1,"ishot":"1","rtime":1519748507000,"issearch":0},{"name":"心安理得","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/29/bf0fee30e247476f96e0ba2fde1b19a6.jpg?m","ringid":"4633286","iscrbt":0,"synopsis":{"sinfo":"王天戈-爱情本就无关对错 只是不适合厮守","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"9085b1cf2e2a769b9ec7b88bd518bf2c","playtime":269,"islike":0,"likenum":37,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4633286","isvip":"0","songer":"王天戈","isringing":1,"isbell":1,"ishot":"0","rtime":1538754976000,"issearch":0},{"name":"去年夏天","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/62.jpeg","ringid":"4408877","iscrbt":0,"synopsis":{"sinfo":"王大毛-夏去了又回来 而人却已不在","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f45dcdb5a6e54f480b73320fe5235fda","playtime":245,"islike":0,"likenum":271,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4408877","isvip":"0","songer":"王大毛","isringing":1,"isbell":1,"ishot":"1","rtime":1532572908000,"issearch":0},{"name":"感谢你曾来过","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/07/11/fffcae7877184a55abff5b2acf42f8c4.jpg?m","ringid":"4347875","iscrbt":0,"synopsis":{"sinfo":"阿涵,Ayo97-感谢你曾来过 就算你是个过客","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"841127362a08c79050499bf1d9128f4c","playtime":236,"islike":0,"likenum":31,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4347875","isvip":"0","songer":"阿涵,Ayo97","isringing":1,"isbell":1,"ishot":"1","rtime":1531294807000,"issearch":0},{"name":"云烟成雨(动画《我是江小白》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/10/ce0736eb2315437ea4cf9647e5794ef8.jpg?m","ringid":"4008278","iscrbt":0,"synopsis":{"sinfo":"房东的猫-抖音当红伤感文艺歌曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137b833e40f9bb60cac8","playtime":240,"islike":0,"likenum":321,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4008278","isvip":"0","songer":"房东的猫","isringing":1,"isbell":1,"ishot":"1","rtime":1517761220000,"issearch":0},{"name":"年少有为","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/16/2a71109b3cdd4127a46b5d99589673ac.jpg?m","ringid":"4375364","iscrbt":0,"synopsis":{"sinfo":"李荣浩-假如我年少有为 知进退","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"87296dd7d81e596430c46a655d329b42","playtime":279,"islike":0,"likenum":98,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4375364","isvip":"0","songer":"李荣浩","isringing":1,"isbell":1,"ishot":"1","rtime":1531935605000,"issearch":0},{"name":"你还怕大雨吗","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/beac052fde224f5094a9ebe7cb9e7830.jpg?m","ringid":"2899623","iscrbt":0,"synopsis":{"sinfo":"周柏豪-你还怕大雨吗 是不是还留短头发","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"734ffc84656230c72eb32e07fbe577d6","playtime":254,"islike":0,"likenum":44,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2899623","isvip":"0","songer":"周柏豪","isringing":1,"isbell":1,"ishot":"1","rtime":1482348208000,"issearch":0},{"name":"时间飞行(超级网剧《镇魂》推广曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/05/9401d4851ed0452280a13b4c431ffc39.jpg?m","ringid":"4266687","iscrbt":0,"synopsis":{"sinfo":"朱一龙,白宇-都市奇幻网剧","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4e296e045717b002a01e1562a4a706ee","playtime":186,"islike":0,"likenum":26,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4266687","isvip":"0","songer":"朱一龙,白宇","isringing":1,"isbell":1,"ishot":"0","rtime":1528191841000,"issearch":0},{"name":"云裳羽衣曲","type":1,"picpath":"https://imgzm.ringbox.cn/ringsetting/ringPic/1536/82/73/1536827368820.jpg","ringid":"4319199","iscrbt":0,"synopsis":{"sinfo":"周深-天籁美声，唯美中国风","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d6b133580bb5bd9374a3888dcc939d56","playtime":296,"islike":0,"likenum":16,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4319199","isvip":"0","songer":"周深","isringing":1,"isbell":1,"ishot":"0","rtime":1530110412000,"issearch":0},{"name":"还你门匙","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/12/12/1609221044237059.jpg?m","ringid":"1807933","iscrbt":0,"synopsis":{"sinfo":"余文乐-做情侣做朋友 都需要漂亮借口","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"8a8e5fbb0253a7302f437196b9c9cd11","playtime":216,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1807933","isvip":"0","songer":"余文乐","isringing":1,"isbell":1,"ishot":"0","rtime":1468130885000,"issearch":0},{"name":"时间里的","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/12/14/69da06d401204002b438d425ebb29b93.jpg?m","ringid":"4282207","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b0e9e980654854c4fd018df66faae264","playtime":343,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4282207","isvip":"0","songer":"马頔","isringing":1,"isbell":1,"ishot":"0","rtime":1528605687000,"issearch":0},{"name":"轮回","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/17/52f9c55484054a20970053235c4e8c33.jpg?m","ringid":"4214094","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f21a9245f575617a290","playtime":247,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4214094","isvip":"0","songer":"刘增瞳","isringing":1,"isbell":1,"ishot":"0","rtime":1526618414000,"issearch":0},{"name":"再遇不到你这样的人","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/10/20/be4393462b01469183dce305e9a6e454.jpg?m","ringid":"3838224","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4cbcc1b5f4ca08caa01e1562a4a706ee","playtime":321,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3838224","isvip":"0","songer":"庄心妍","isringing":1,"isbell":1,"ishot":"0","rtime":1505752038000,"issearch":0},{"name":"离人愁","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/19/47f822def22144ed8b452476dd9c619a.jpg?m","ringid":"4308423","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"44c56241e0f22d0fab6cce57a4afda85","playtime":233,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4308423","isvip":"0","songer":"李袁杰,金南玲","isringing":1,"isbell":1,"ishot":"0","rtime":1529469609000,"issearch":0},{"name":"如梦","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/10/18/1610181644154812.jpg?m","ringid":"2874357","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ad1f28ca3d68f218a2cdc231e7d487ca","playtime":280,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2874357","isvip":"0","songer":"蒋雪儿","isringing":1,"isbell":1,"ishot":"0","rtime":1481040014000,"issearch":0},{"name":"半城烟沙(网游《天龙八部2》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/08/15/1609221121141086.jpg?m","ringid":"2401700","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"0a25e5667006a3b436d4af2e46c678ea","playtime":295,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2401700","isvip":"0","songer":"许嵩","isringing":1,"isbell":1,"ishot":"0","rtime":1468247697000,"issearch":0},{"name":"你的样子","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/02/1609221116065599.jpg?m","ringid":"2387527","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"2bfa19fbea2630a4b0f8eba1592a41e0","playtime":277,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2387527","isvip":"0","songer":"齐秦","isringing":1,"isbell":1,"ishot":"0","rtime":1468245379000,"issearch":0},{"name":"无常","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/b8590e766350482fae26a47cdec53cfb.jpg?m","ringid":"1927346","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"35d9dbb57b41ab02978b178cba6c5ac0","playtime":260,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1927346","isvip":"0","songer":"田馥甄","isringing":1,"isbell":1,"ishot":"0","rtime":1468156723000,"issearch":0},{"name":"等一分钟","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/29/160922110707643.jpg?m","ringid":"4001900","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a80bd378943debe3634d036094b0a7fc","playtime":240,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4001900","isvip":"0","songer":"徐誉滕","isringing":1,"isbell":1,"ishot":"0","rtime":1517415958000,"issearch":0},{"name":"忽然之间","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/8ee85eb39fc14fd6aeb68b49cfce5d32.jpg?m","ringid":"1297833","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b957432baaac42d8dd6561cfffe7e197","playtime":235,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1297833","isvip":"0","songer":"苏醒","isringing":1,"isbell":1,"ishot":"0","rtime":1468046997000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"2","list_name":"总以为来日方长，却忘了世事无常","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1541/49/01/1541490113060.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1541","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1541/49/01/1541490127036.png","list_content":"有些人，原以为可以见面；有些事，原以为可以一直继续。可是我们都忘了，忘了时间的残酷，忘了人生的短暂。也许在我们转身的那个刹那，有些人，就再也见不到了。世界一点也不善良，别再等来日方长。","total_count":30,"list_count":20},"hash":"","code":1,"msg":"","timespan":1541744912038}',
            '
{"data":{"list":[{"name":"没离开过(央视星光璀璨新民歌演唱会)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2016/12/22/1612151804206855.jpg?m","ringid":"2933484","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"07766ae9bd602cf327b81d8733d767f7","playtime":197,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2933484","isvip":"0","songer":"林志炫","isringing":1,"isbell":1,"ishot":"0","rtime":1483617495000,"issearch":0},{"name":"岁月神偷 (Live)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/03/22/2e88428996ab477a95a62c0f16172aea.jpg?m","ringid":"4135590","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"3b77fd5b8c3fc1527458d891fe9eac24","playtime":142,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4135590","isvip":"0","songer":"周笔畅","isringing":1,"isbell":1,"ishot":"0","rtime":1524327754000,"issearch":0},{"name":"时间煮雨(小时代官方宣传曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/11/22/1609221210176849.jpg?m","ringid":"2414856","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"feb921190eb7fe5dd2a2e9f40b6351b6","playtime":247,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2414856","isvip":"0","songer":"郁可唯","isringing":1,"isbell":1,"ishot":"0","rtime":1468249805000,"issearch":0},{"name":"忘记时间","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/04/f3cbeb5da4d547b190b38e0e4815c8d9.jpg?m","ringid":"1802529","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5f69ddc07a92b8261929e40b1e9ac30c","playtime":272,"islike":0,"likenum":6,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1802529","isvip":"0","songer":"胡歌","isringing":1,"isbell":1,"ishot":"0","rtime":1468130004000,"issearch":0},{"name":"我只是慢慢走远","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/17/256d4e007bbb407790d8f22e4c47c69e.jpg?m","ringid":"2886588","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f6705fab8ecf777a0fae99f91e6974d8","playtime":330,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2886588","isvip":"0","songer":"张碧晨","isringing":1,"isbell":1,"ishot":"0","rtime":1481134183000,"issearch":0},{"name":"时间有泪(歌手)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/03/04/17030421371651.jpg?m","ringid":"3084904","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"963a91e2da55d8ec89c225ae9c96b67b","playtime":320,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3084904","isvip":"0","songer":"张碧晨","isringing":1,"isbell":1,"ishot":"0","rtime":1489083159000,"issearch":0},{"name":"唐人(唐朝好男人主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/02/09/f372ef28e72a4c44a3fe9d67c79c2ab6.jpg?m","ringid":"2283605","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a561b3dacb76400f9f32d00e97715fea","playtime":227,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2283605","isvip":"0","songer":"孙子涵","isringing":1,"isbell":1,"ishot":"0","rtime":1468220872000,"issearch":0},{"name":"断点","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/06/16092210154586.jpg?m","ringid":"1810620","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ae518cca359aee07dc859e0f4f4170ee","playtime":269,"islike":0,"likenum":5,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1810620","isvip":"0","songer":"张敬轩","isringing":1,"isbell":1,"ishot":"0","rtime":1468131342000,"issearch":0},{"name":"偶阵雨(网络电影《爱从心说 温润心田》插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/02/1609221127569044.jpg?m","ringid":"2942823","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5efccfcb2cc3fa680c2b50958d717b73","playtime":263,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2942823","isvip":"0","songer":"梁静茹","isringing":1,"isbell":1,"ishot":"0","rtime":1485910214000,"issearch":0},{"name":"往日时光","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/27/ca1da8cfc0044fbf88c456de224a1df6.jpg?m","ringid":"1692392","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4691039e4c3a94252484673c4b5e13c7","playtime":245,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1692392","isvip":"0","songer":"谭维维","isringing":1,"isbell":1,"ishot":"0","rtime":1468107819000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"2","list_name":"总以为来日方长，却忘了世事无常","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1541/49/01/1541490113060.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1541","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1541/49/01/1541490127036.png","list_content":"有些人，原以为可以见面；有些事，原以为可以一直继续。可是我们都忘了，忘了时间的残酷，忘了人生的短暂。也许在我们转身的那个刹那，有些人，就再也见不到了。世界一点也不善良，别再等来日方长。","total_count":30,"list_count":10},"hash":"","code":1,"msg":"","timespan":1541744914984}',
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
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=447&t=0%202&p=1&pn=40',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=450&t=0%202&p=1&pn=40',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=355&t=0%202&p=1&pn=40',
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