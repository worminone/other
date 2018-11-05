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
            '{"data":{"list":[{"name":"沙漠骆驼(抖音版)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/29/8fb9e6a4889e4ff8a17ada18e6af7902.jpg?m","ringid":"4694720","iscrbt":0,"synopsis":{"sinfo":"展展与罗罗-什么鬼魅传说 什么魑魅魍魉妖魔","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"efc04082a75341f3639d891a3b55dea2","playtime":338,"islike":0,"likenum":133,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4694720","isvip":"0","songer":"展展与罗罗","isringing":1,"isbell":1,"ishot":"1","rtime":1539539701000,"issearch":0},{"name":"可不可以","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/04/4645e7314f2d4d8697cecbd903e484c6.jpg?m","ringid":"4467097","iscrbt":0,"synopsis":{"sinfo":"张紫豪-我可以接受你的所有小脾气","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f21aeed426ef78e89ca","playtime":241,"islike":0,"likenum":417,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4467097","isvip":"0","songer":"张紫豪","isringing":1,"isbell":1,"ishot":"1","rtime":1534826467000,"issearch":0},{"name":"一百万个可能","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/11/c9d2ff7702484ea48adee49f1f79a788.jpg?m","ringid":"4561859","iscrbt":0,"synopsis":{"sinfo":"虎二-在一瞬间有一百万个可能","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a1918d909d36bd0caf1bc797ccbe04ba","playtime":242,"islike":0,"likenum":146,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4561859","isvip":"0","songer":"虎二","isringing":1,"isbell":1,"ishot":"0","rtime":1536810186000,"issearch":0}],"list_editer":"","list_islike":"0","list_likenum":"0","list_name":"火爆热歌","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/16/1513661660522.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1132","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/16/1513661671117.png","list_content":"","total_count":244,"list_count":3},"hash":"","code":1,"msg":"","timespan":1541386419226}',

            '{"data":{"list":[{"name":"心做し","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/13/00ad1cadf2e74c42a5f95edc92901b93.jpg?m","ringid":"3966097","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b5e38eb53a9a78d3211e0338fcb51412","playtime":276,"islike":0,"likenum":103,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3966097","isvip":"0","songer":"Akane","isringing":1,"isbell":1,"ishot":"0","rtime":1515169237000,"issearch":0},{"name":"みかんの花咲く丘","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/422.jpeg","ringid":"4110746","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"10cdf13e9e9fe61c756a66e650ae8a1b","playtime":135,"islike":0,"likenum":8,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4110746","isvip":"0","songer":"由纪纱织,安田祥子","isringing":1,"isbell":1,"ishot":"0","rtime":1523290808000,"issearch":0},{"name":"この街で","type":1,"picpath":"https://imgzm.ringbox.cn/ringsetting/ringPic/1536/82/85/1536828568121.jpg","ringid":"3872623","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"80bd656098b667c9cf3d3e190cab4477","playtime":278,"islike":0,"likenum":20,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3872623","isvip":"0","songer":"Dreams Come True","isringing":1,"isbell":1,"ishot":"0","rtime":1510071604000,"issearch":0}],"list_editer":"","list_islike":"0","list_likenum":"0","list_name":"日韩流行","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/18/1513661898054.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1035","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/19/1513661909436.png","list_content":"","total_count":61,"list_count":3},"hash":"","code":1,"msg":"","timespan":1541386419218}',

            '{"data":{"list":[{"name":"恰好(电影《功夫联盟》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/21/18f110e63dd547ac817f959a63eb0773.jpg?m","ringid":"4724741","iscrbt":0,"synopsis":{"sinfo":"A-Lin-最美的安排也许是没有安排 一切都是恰好","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7e6ca9195d4ce633c80fdc2207e12ab5","playtime":255,"islike":0,"likenum":6,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4724741","isvip":"0","songer":"A-Lin","isringing":1,"isbell":1,"ishot":"0","rtime":1540144550000,"issearch":0},{"name":"影响(电视剧《创业时代》爱情主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/18/08d643c9f8b04af4b355fc950d794f42.jpg?m","ringid":"4713973","iscrbt":0,"synopsis":{"sinfo":"陈粒-我假装不在乎悲伤 却眼含泪光","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5dbca69e8ed2b167b96","playtime":225,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4713973","isvip":"0","songer":"陈粒","isringing":1,"isbell":1,"ishot":"0","rtime":1539827781000,"issearch":0},{"name":"永夜(网络剧《将夜》推广曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/25/dd3bd85bc0014ed583d3ec98b27ffd00.jpg?m","ringid":"4741075","iscrbt":0,"synopsis":{"sinfo":"谭维维-或许前路永夜 我们也要前进","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"465319747d000770a8423cd4b77b0c22","playtime":254,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4741075","isvip":"0","songer":"谭维维","isringing":1,"isbell":1,"ishot":"0","rtime":1540529926000,"issearch":0}],"list_editer":"","list_islike":"0","list_likenum":"0","list_name":"影视综艺","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/20/1513662030113.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=298","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/20/1513662039670.png","list_content":"影视综艺榜","total_count":178,"list_count":3},"hash":"","code":1,"msg":"","timespan":1541386419221}',

            '{"data":{"list":[{"name":"学猫叫","type":1,"picpath":"https://imgzm.ringbox.cn/ringsetting/ringPic/1536/82/78/1536827869912.jpg","ringid":"4184242","iscrbt":0,"synopsis":{"sinfo":"小峰峰,小潘潘-抖音热歌我们一起喵喵喵喵","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a38de7006d65caa9fed","playtime":209,"islike":0,"likenum":223,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4184242","isvip":"0","songer":"小峰峰,小潘潘","isringing":1,"isbell":1,"ishot":"1","rtime":1525801233000,"issearch":0},{"name":"我已经爱上你 (女版)","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/293.jpeg","ringid":"4525571","iscrbt":0,"synopsis":{"sinfo":"李伊曼-我已经爱上你 渴望着在一起","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"3291aad5857781a41929e40b1e9ac30c","playtime":148,"islike":0,"likenum":165,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4525571","isvip":"0","songer":"李伊曼","isringing":1,"isbell":1,"ishot":"0","rtime":1535974903000,"issearch":0},{"name":"陷阱","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/08/07/152445b64d584f87bfbd1eb8e2e1169d.jpg?m","ringid":"4421880","iscrbt":0,"synopsis":{"sinfo":"王北车-我不曾爱过你 我自己骗自己","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a389c270ae311b08066","playtime":280,"islike":0,"likenum":290,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4421880","isvip":"0","songer":"王北车","isringing":1,"isbell":1,"ishot":"1","rtime":1533350403000,"issearch":0}],"list_editer":"","list_islike":"0","list_likenum":"0","list_name":"网络红歌","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/19/1513661961333.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1038","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/19/1513661946346.png","list_content":"","total_count":111,"list_count":3},"hash":"","code":1,"msg":"","timespan":1541386419462}',

            '{"data":{"list":[{"name":"Waste It On Me","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/25/21bd39d3295941358ea4d89ee733ac10.jpg?m","ringid":"4735862","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7e6ca9195d4ce63385b75cf6f2586417","playtime":192,"islike":0,"likenum":4,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4735862","isvip":"0","songer":"Steve Aoki,防弹少年团","isringing":1,"isbell":1,"ishot":"0","rtime":1540479279000,"issearch":0},{"name":"Natural","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/07/19/c9a089a0436c447b8636c1fc8696320e.jpg?m","ringid":"4375020","iscrbt":0,"synopsis":{"sinfo":"Imagine Dragons-梦龙全新单曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"1beb81dfecc90fa49b7a6e3bc2fa43fc","playtime":189,"islike":0,"likenum":19,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4375020","isvip":"0","songer":"Imagine Dragons","isringing":1,"isbell":1,"ishot":"0","rtime":1531903201000,"issearch":0},{"name":"Shadow（如影随形）","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/06/1c4859bf4f704b14984241b5a0ad0298.jpg?m","ringid":"4401139","iscrbt":0,"synopsis":{"sinfo":"朱婧汐-一秒抖腿 无限循环","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"6ba8c33eaf1da5463ac6ce5ca2371f45","playtime":208,"islike":0,"likenum":43,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4401139","isvip":"0","songer":"朱婧汐","isringing":1,"isbell":1,"ishot":"0","rtime":1532673602000,"issearch":0}],"list_editer":"","list_islike":"0","list_likenum":"0","list_name":"欧美流行","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/33/1513663395264.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1034","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1513/66/18/1513661831352.png","list_content":"","total_count":124,"list_count":3},"hash":"","code":1,"msg":"","timespan":1541386419465}',

            '{"data":{"list":[{"name":"后来 (电视剧《真情》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/28/1609221024401117.jpg?m","ringid":"2213158","iscrbt":0,"synopsis":{"sinfo":"刘若英-一旦错过就不再","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"125acf2b1d83bfa1d57419cc07719b93","playtime":339,"islike":0,"likenum":37,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2213158","isvip":"0","songer":"刘若英","isringing":1,"isbell":1,"ishot":"0","rtime":1468209753000,"issearch":0},{"name":"欢乐颂","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/19/945b8f2bc5a348d185c90f479999389b.jpg?m","ringid":"3972739","iscrbt":0,"synopsis":{"sinfo":"陈咏,陈娇-摇摆摇摆，节奏不要停","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137bb201abb8cae145b3","playtime":207,"islike":0,"likenum":5,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3972739","isvip":"0","songer":"陈咏,陈娇","isringing":1,"isbell":1,"ishot":"0","rtime":1515774263000,"issearch":0},{"name":"Shape Of You","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/04/22/1702182143004163.jpg?m","ringid":"2943042","iscrbt":0,"synopsis":{"sinfo":"Ed Sheeran-黄老板洗脑神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"8d4d5dde06860aed6c8f47471c099c9a","playtime":233,"islike":0,"likenum":35,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2943042","isvip":"0","songer":"Ed Sheeran","isringing":1,"isbell":1,"ishot":"0","rtime":1485910253000,"issearch":0},{"name":"再也没有","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/78.jpeg","ringid":"3828602","iscrbt":0,"synopsis":{"sinfo":"Ryan.B,AY杨佬叁-抖音神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4e296e045717b002c0740e44b7634798","playtime":212,"islike":0,"likenum":57,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3828602","isvip":"0","songer":"Ryan.B,AY杨佬叁","isringing":1,"isbell":1,"ishot":"0","rtime":1504110686000,"issearch":0},{"name":"多想留在你身边","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/24/acc319f571994948b6bc30a95b78df74.jpg?m","ringid":"3794492","iscrbt":0,"synopsis":{"sinfo":"刘增瞳-温情唱响爱的篇章","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df7275831bacd88aedf7","playtime":212,"islike":0,"likenum":40,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3794492","isvip":"0","songer":"刘增瞳","isringing":1,"isbell":1,"ishot":"0","rtime":1502974792000,"issearch":0},{"name":"危险女孩","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/29/52a44aa0ec8d40be980338020bdf1500.jpg?m","ringid":"4018746","iscrbt":0,"synopsis":{"sinfo":"CE-迷幻曲风，骚气十足","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137ba7ea1fc278874fc5","playtime":185,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4018746","isvip":"0","songer":"CE","isringing":1,"isbell":1,"ishot":"0","rtime":1518106866000,"issearch":0},{"name":"三角题","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/10/20/9d92d767906d46099a36dd8dd68d1f32.jpg?m","ringid":"3843357","iscrbt":0,"synopsis":{"sinfo":"二珂-网络热歌","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f0303294e1ab76901b8678ac65b1ce56","playtime":265,"islike":0,"likenum":9,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3843357","isvip":"0","songer":"二珂","isringing":1,"isbell":1,"ishot":"0","rtime":1506529231000,"issearch":0},{"name":"全部都是你","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/08/03/caea589c9f084a66af3e6b386931711b.jpg?m","ringid":"3828597","iscrbt":0,"synopsis":{"sinfo":"Dragon Pig,CLOUD WANG,CNBALLER","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4e296e045717b0021a841241c15b4b92","playtime":203,"islike":0,"likenum":84,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3828597","isvip":"0","songer":"Dragon Pig,CLOUD WANG,CNBALLER","isringing":1,"isbell":1,"ishot":"0","rtime":1504110684000,"issearch":0},{"name":"Panama","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/02/12/ff8dbf518d304f488b8190f76e2153a0.jpg?m","ringid":"3879736","iscrbt":0,"synopsis":{"sinfo":"Matteo-全网爆红C哩C哩","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df72ba95f046b4f05943","playtime":200,"islike":0,"likenum":62,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3879736","isvip":"0","songer":"Matteo","isringing":1,"isbell":1,"ishot":"0","rtime":1510590454000,"issearch":0},{"name":"还不是因为你长得不好看","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/05/02/f1b0153dfd624637976410754b8a8bb2.jpg?m","ringid":"3779831","iscrbt":0,"synopsis":{"sinfo":"潇儿,鲲鹏兄弟,王嘉欣-扎心了老铁","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"15219101b5fd8c7f3f2b73ec96a71b25","playtime":194,"islike":0,"likenum":7,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3779831","isvip":"0","songer":"潇儿,鲲鹏兄弟,王嘉欣","isringing":1,"isbell":1,"ishot":"0","rtime":1502844884000,"issearch":0},{"name":"Wannabe(电影《美国派4：美国重逢》插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/30/66129e6bbe734e35acd6a66df8145bd9.jpg?m","ringid":"3034264","iscrbt":0,"synopsis":{"sinfo":"Spice Girls-《美国派4：美国重逢》插曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"99b774dd765304c65bb934c29cef1ad9","playtime":172,"islike":0,"likenum":20,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3034264","isvip":"0","songer":"Spice Girls","isringing":1,"isbell":1,"ishot":"0","rtime":1488193199000,"issearch":0},{"name":"短发","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/12/033cf51bda3c4b8a806a707d672b8fe2.jpg?m","ringid":"3977463","iscrbt":0,"synopsis":{"sinfo":"LAMPHO-抖音神曲 短发姑娘","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137b510487f6c0941cb8","playtime":192,"islike":0,"likenum":44,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3977463","isvip":"0","songer":"LAMPHO","isringing":1,"isbell":1,"ishot":"0","rtime":1515992273000,"issearch":0},{"name":"Panda","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/03/18/1603181840127198.jpg?m","ringid":"1055147","iscrbt":0,"synopsis":{"sinfo":"Desiigner-国宝の蔑视","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d503143f7dd204894a49abcc09df0381","playtime":226,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1055147","isvip":"0","songer":"Desiigner","isringing":1,"isbell":1,"ishot":"0","rtime":1467978297000,"issearch":0},{"name":"带你去旅行","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/10/13/dcd14800b6f9412fb40c7a68587d51fe.jpg?m","ringid":"3845452","iscrbt":0,"synopsis":{"sinfo":"校长-抖音爆红撩妹神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df727d96d43a53d242d1","playtime":225,"islike":0,"likenum":45,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3845452","isvip":"0","songer":"校长","isringing":1,"isbell":1,"ishot":"0","rtime":1506788498000,"issearch":0},{"name":"走心小卖家","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/26/48558eb4671f4fcfa39c8aaeed03c027.jpg?m","ringid":"3977462","iscrbt":0,"synopsis":{"sinfo":"张雪飞-抖音热曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137b0809be114f5ebfc7","playtime":221,"islike":0,"likenum":17,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3977462","isvip":"0","songer":"张雪飞","isringing":1,"isbell":1,"ishot":"1","rtime":1515992175000,"issearch":0},{"name":"Dark Side","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/27/6b6f835bacb345b9b7467bd02c56bbbf.jpg?m","ringid":"3008683","iscrbt":0,"synopsis":{"sinfo":"Phoebe Ryan-可爱声线+动感音乐","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"95ca0a2ebf2680eb9841333a123bb844","playtime":204,"islike":0,"likenum":4,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3008683","isvip":"0","songer":"Phoebe Ryan","isringing":1,"isbell":1,"ishot":"0","rtime":1488190409000,"issearch":0},{"name":"尽头","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/10/11/ed2f95f7229c4e5b85c504a97f8a1df9.jpg?m","ringid":"3845451","iscrbt":0,"synopsis":{"sinfo":"赵方婧-抖音热门神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df72a679433c6bd5fcab","playtime":256,"islike":0,"likenum":19,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3845451","isvip":"0","songer":"赵方婧","isringing":1,"isbell":1,"ishot":"1","rtime":1506788498000,"issearch":0},{"name":"最美情侣","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/09/04/b08d6b4861ea4a8f8d066d3085349dde.jpg?m","ringid":"3786023","iscrbt":0,"synopsis":{"sinfo":"白小白-别点开这歌 实在太虐狗了","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3031e881b8faf32ab2d0370883a5dd8","playtime":241,"islike":0,"likenum":70,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3786023","isvip":"0","songer":"白小白","isringing":1,"isbell":1,"ishot":"0","rtime":1502847496000,"issearch":0},{"name":"说散就散(电影《前任3：再见前任》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/04/4634efa50f0242b6a86b49c6ab4b07ab.jpg?m","ringid":"3934347","iscrbt":0,"synopsis":{"sinfo":"袁娅维-电影《前任3:再见前任》主题曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ea50d5ea6d57800754b869511c6b8886","playtime":242,"islike":0,"likenum":112,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3934347","isvip":"0","songer":"袁娅维","isringing":1,"isbell":1,"ishot":"0","rtime":1513268512000,"issearch":0},{"name":"Es Rappelt Im Karton","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2015/10/17/1509241022315516.jpg?m","ringid":"1370621","iscrbt":0,"synopsis":{"sinfo":"Pixie Paris","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c049b392fda06ca833eef2fa3fdba952","playtime":204,"islike":0,"likenum":36,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1370621","isvip":"0","songer":"Pixie Paris","isringing":1,"isbell":1,"ishot":"0","rtime":1468057850000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"0","list_name":"抖音热门","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/33/1518323303474.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1377","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/32/1518323290961.png","list_content":"抖音热门歌曲榜","total_count":110,"list_count":20},"hash":"","code":1,"msg":"","timespan":1541386443442}',

            '{"data":{"list":[{"name":"If You Feel My Love","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/05/25/1605250957027774.jpg?m","ringid":"2623596","iscrbt":0,"synopsis":{"sinfo":"Blaxy Girls","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"67f872bca97b9956ec2564d9d3c18ef7","playtime":205,"islike":0,"likenum":21,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2623596","isvip":"0","songer":"Blaxy Girls","isringing":1,"isbell":1,"ishot":"0","rtime":1470792601000,"issearch":0},{"name":"春风十里","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/12/13/d4ffa2e0ebc5480190598a6ebf2cfc07.jpg?m","ringid":"3932560","iscrbt":0,"synopsis":{"sinfo":"鹿先森乐队-暖声治愈，愿一切温暖美好","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4aa1412dbb8d022d4b01b9997b5ac517","playtime":375,"islike":0,"likenum":19,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3932560","isvip":"0","songer":"鹿先森乐队","isringing":1,"isbell":1,"ishot":"0","rtime":1513182363000,"issearch":0},{"name":"Handclap","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/30/1611190028167846.jpg?m","ringid":"1195602","iscrbt":0,"synopsis":{"sinfo":"Fitz & the Tantrums-吃鸡战歌绝地求生眨眼专属BGM","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ba673b828be51a2fd39466726d5bf74c","playtime":193,"islike":0,"likenum":67,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1195602","isvip":"0","songer":"Fitz & the Tantrums","isringing":1,"isbell":1,"ishot":"1","rtime":1468024396000,"issearch":0},{"name":"一晃眼","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/11/15/d6c541e21dbf44a786190309f7240860.jpg?m","ringid":"3885342","iscrbt":0,"synopsis":{"sinfo":"卫兰-人生不过一晃眼","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ea50d5ea6d578007c122ff9401d12030","playtime":292,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3885342","isvip":"0","songer":"卫兰","isringing":1,"isbell":1,"ishot":"0","rtime":1510849427000,"issearch":0},{"name":"空空如也","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/15/1863227c562b4d3ab52f0412a86d3008.jpg?m","ringid":"3956748","iscrbt":0,"synopsis":{"sinfo":"胡66-抖音神曲就这样空空过了一夜","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df72913ea933a0bf53be","playtime":211,"islike":0,"likenum":139,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3956748","isvip":"0","songer":"胡66","isringing":1,"isbell":1,"ishot":"1","rtime":1514046304000,"issearch":0},{"name":"爱的就是你","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/07/08/1609221712407837.jpg?m","ringid":"3954243","iscrbt":0,"synopsis":{"sinfo":"刘佳-浪漫甜蜜，爱的就是你","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"0db0491c5574ba74776ff08afbb66a83","playtime":273,"islike":0,"likenum":15,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3954243","isvip":"0","songer":"刘佳","isringing":1,"isbell":1,"ishot":"0","rtime":1513959926000,"issearch":0},{"name":"我们不一样","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/10/11/62f72bad586d421aa28e01f9531cc9e3.jpg?m","ringid":"3958053","iscrbt":0,"synopsis":{"sinfo":"大壮-爆红洗脑神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4985564f2f854dc44c76b73b7918d143","playtime":270,"islike":0,"likenum":26,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3958053","isvip":"0","songer":"大壮","isringing":1,"isbell":1,"ishot":"0","rtime":1514305459000,"issearch":0},{"name":"Colorful World","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/06/22/13f5ccc89c3d4471b7c5c6d041f0c6fe.jpg?m","ringid":"3007145","iscrbt":0,"synopsis":{"sinfo":"Keziah Jones","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ca61fcb09f1ee22b291c7956dd0a3ae8","playtime":0,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3007145","isvip":"0","songer":"Keziah Jones","isringing":1,"isbell":1,"ishot":"0","rtime":1488181702000,"issearch":0},{"name":"Me Too","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2016/05/19/1605051720092377.jpg?m","ringid":"1607970","iscrbt":0,"synopsis":{"sinfo":"Me Three","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"defbe53182c42a04a5bf4feff736130a","playtime":181,"islike":0,"likenum":5,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1607970","isvip":"0","songer":"Meghan Trainor","isringing":1,"isbell":1,"ishot":"0","rtime":1468094323000,"issearch":0},{"name":"红昭愿","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/13/df00452d44d74c2fa9badb268bc31c30.jpg?m","ringid":"3942770","iscrbt":0,"synopsis":{"sinfo":"音阙诗听-抖音爆红中国风R&B","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"9743e2384760fe281a841241c15b4b92","playtime":173,"islike":0,"likenum":163,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3942770","isvip":"0","songer":"音阙诗听","isringing":1,"isbell":1,"ishot":"0","rtime":1513441333000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"0","list_name":"抖音热门","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/33/1518323303474.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1377","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/32/1518323290961.png","list_content":"抖音热门歌曲榜","total_count":110,"list_count":10},"hash":"","code":1,"msg":"","timespan":1541386445094}',

            '{"data":{"list":[{"name":"Something Just Like This","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/12/4b4d3f637135466f93bf213f31654a52.jpg?m","ringid":"3052352","iscrbt":0,"synopsis":{"sinfo":"The Chainsmokers,Coldplay","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"1e30fbcfc744e4530e9ba0eaf28e5615","playtime":247,"islike":0,"likenum":175,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3052352","isvip":"0","songer":"The Chainsmokers,Coldplay","isringing":1,"isbell":1,"ishot":"0","rtime":1488195085000,"issearch":0},{"name":"啷个哩个啷","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2016/10/18/161018131749687.jpg?m","ringid":"2939919","iscrbt":0,"synopsis":{"sinfo":"鹏泊-“单身狗”的快乐进行曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"cec8223754b179c980326b20bc6d814b","playtime":187,"islike":0,"likenum":50,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2939919","isvip":"0","songer":"鹏泊","isringing":1,"isbell":1,"ishot":"0","rtime":1483635421000,"issearch":0},{"name":"回忆总想哭","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/07/28/4a81961d3f134be5b6c5f40b35a5da30.jpg?m","ringid":"3794509","iscrbt":0,"synopsis":{"sinfo":"南宫嘉骏,姜玉阳-抖音伤感情歌","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df728bd3cc7ba368d654","playtime":292,"islike":0,"likenum":28,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3794509","isvip":"0","songer":"南宫嘉骏,姜玉阳","isringing":1,"isbell":1,"ishot":"0","rtime":1502974798000,"issearch":0},{"name":"Lemon","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/08/b4f2df22ca4848d88f4ffb60992a70c9.jpg?m","ringid":"3873452","iscrbt":0,"synopsis":{"sinfo":"N.E.R.D.,Rihanna-抖音神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7b2be285e7c8d9b994af1466dff11607","playtime":219,"islike":0,"likenum":57,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3873452","isvip":"0","songer":"N.E.R.D.,Rihanna","isringing":1,"isbell":1,"ishot":"0","rtime":1510071683000,"issearch":0},{"name":"Attention","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/20/189b651f5f614beda5e18adb83c8257b.jpg?m","ringid":"3181614","iscrbt":0,"synopsis":{"sinfo":"Charlie Puth-撩人最佳","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"aff7c66cd58b283f028481dc3716750e","playtime":211,"islike":0,"likenum":92,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3181614","isvip":"0","songer":"Charlie Puth","isringing":1,"isbell":1,"ishot":"0","rtime":1495815657000,"issearch":0},{"name":"2U","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/06/13/9ad30f6869b84dfa921a15feb9b7755c.jpg?m","ringid":"3272028","iscrbt":0,"synopsis":{"sinfo":"Justin Bieber,David Guetta","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"aff7c66cd58b283f4534a54b4fba5b18","playtime":194,"islike":0,"likenum":37,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3272028","isvip":"0","songer":"Justin Bieber,David Guetta","isringing":1,"isbell":1,"ishot":"1","rtime":1497457259000,"issearch":0},{"name":"广东爱情故事","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/02/02/bef84152a2e34eb6936d9f0234bbe1bc.jpg?m","ringid":"3948635","iscrbt":0,"synopsis":{"sinfo":"广东雨神-网络爆红","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"6b7154c0894229d8a97b0f60296a616f","playtime":214,"islike":0,"likenum":69,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3948635","isvip":"0","songer":"广东雨神","isringing":1,"isbell":1,"ishot":"0","rtime":1513786902000,"issearch":0},{"name":"How Long","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/20/189b651f5f614beda5e18adb83c8257b.jpg?m","ringid":"3857440","iscrbt":0,"synopsis":{"sinfo":"Charlie Puth-抖音年度神曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"ea50d5ea6d57800772c63d000a6f7b5a","playtime":198,"islike":0,"likenum":128,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3857440","isvip":"0","songer":"Charlie Puth","isringing":1,"isbell":1,"ishot":"1","rtime":1507998244000,"issearch":0},{"name":"只想对你说","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/492.jpeg","ringid":"4179546","iscrbt":0,"synopsis":{"sinfo":"掌嘴,张雪飞,Lil-7-抖音热门说唱","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"abde86990c4b1f11b6c75e75f13ece25","playtime":264,"islike":0,"likenum":23,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4179546","isvip":"0","songer":"掌嘴,张雪飞,Lil-7","isringing":1,"isbell":1,"ishot":"0","rtime":1525621222000,"issearch":0},{"name":"追光者(电视剧《夏至未至》插曲)","type":1,"picpath":"https://imgzm.ringbox.cn/ringsetting/ringPic/1536/82/78/1536827842252.jpg","ringid":"4123869","iscrbt":0,"synopsis":{"sinfo":"岑宁儿-天籁之音","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"981a2e7af551b145b4991e24d4abf3e3","playtime":235,"islike":0,"likenum":74,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4123869","isvip":"0","songer":"岑宁儿","isringing":1,"isbell":1,"ishot":"0","rtime":1523723011000,"issearch":0},{"name":"Despacito","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/31/8fd8ca1a710e45f39525d1026812429b.jpg?m","ringid":"2954578","iscrbt":0,"synopsis":{"sinfo":"Daddy Yankee,Luis Fonsi","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e4e8b69f1801c3b29650a9c1b762b9c8","playtime":228,"islike":0,"likenum":152,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2954578","isvip":"0","songer":"Daddy Yankee,Luis Fonsi","isringing":1,"isbell":1,"ishot":"0","rtime":1485912822000,"issearch":0},{"name":"Everybody Hates Me","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/08/02/89ceffbcf48e4fd2bc7c78eb76707d90.jpg?m","ringid":"4077247","iscrbt":0,"synopsis":{"sinfo":"The Chainsmokers-欧美火爆新曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b9b291f66157707271d81fdbb5e64344","playtime":224,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4077247","isvip":"0","songer":"The Chainsmokers","isringing":1,"isbell":1,"ishot":"0","rtime":1521303767000,"issearch":0},{"name":"Havana","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/12/1becf0cdd07242a186f042757e4ff770.jpg?m","ringid":"3982343","iscrbt":0,"synopsis":{"sinfo":"Young Thug,Camila Cabello","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7b2be285e7c8d9b92c633b80a86e159b","playtime":216,"islike":0,"likenum":175,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3982343","isvip":"0","songer":"Young Thug,Camila Cabello","isringing":1,"isbell":1,"ishot":"0","rtime":1516244471000,"issearch":0},{"name":"我们(电影《后来的我们》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/04/11/ca6ab5c2455847ca9ea8082f514c8c24.jpg?m","ringid":"4113902","iscrbt":0,"synopsis":{"sinfo":"陈奕迅-实力派歌王","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c229e159dd60591d776ff08afbb66a83","playtime":260,"islike":0,"likenum":37,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4113902","isvip":"0","songer":"陈奕迅","isringing":1,"isbell":1,"ishot":"0","rtime":1523463719000,"issearch":0},{"name":"你,好不好(TVBS连续剧《遗憾拼图》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/d7aa01efdc3b41a8a44fcdbc413f7fc9.jpg?m","ringid":"1608012","iscrbt":0,"synopsis":{"sinfo":"周兴哲-情歌暖男","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"defbe53182c42a04a9e4777ac8b89472","playtime":287,"islike":0,"likenum":63,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=1608012","isvip":"0","songer":"周兴哲","isringing":1,"isbell":1,"ishot":"0","rtime":1468094330000,"issearch":0},{"name":"烟火里的尘埃","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/30/1704071554356823.jpg?m","ringid":"2414677","iscrbt":0,"synopsis":{"sinfo":"华晨宇-实力演绎超强唱功","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"feb921190eb7fe5d0b1a5a34eaddec2e","playtime":321,"islike":0,"likenum":137,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2414677","isvip":"0","songer":"华晨宇","isringing":1,"isbell":1,"ishot":"0","rtime":1468249778000,"issearch":0},{"name":"离人愁","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/03/12/f54913bc7c914a3db3cc6a04a6acdf33.jpg?m","ringid":"4071667","iscrbt":0,"synopsis":{"sinfo":"李袁杰-抖音爆红，我应在江湖悠悠","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5dbe886099e3c722ec5","playtime":0,"islike":0,"likenum":51,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4071667","isvip":"0","songer":"李袁杰","isringing":1,"isbell":1,"ishot":"0","rtime":1520958096000,"issearch":0},{"name":"白羊","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/04/24/18988e3edd6a4bdc86b9c2574b59157e.jpg?m","ringid":"4146868","iscrbt":0,"synopsis":{"sinfo":"曲肖冰-多热烈的白羊","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a38d8c0b3f0f28e19f7","playtime":168,"islike":0,"likenum":68,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4146868","isvip":"0","songer":"曲肖冰","isringing":1,"isbell":1,"ishot":"0","rtime":1524673574000,"issearch":0},{"name":"离人愁","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/04/25/8045a460d3da4f8ca021432d92e16e0f.jpg?m","ringid":"4148088","iscrbt":0,"synopsis":{"sinfo":"曲肖冰-悠扬古风女声","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a1918d909d36bd0c9cb8b3ed28f727d6","playtime":227,"islike":0,"likenum":87,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4148088","isvip":"0","songer":"曲肖冰","isringing":1,"isbell":1,"ishot":"0","rtime":1524789624000,"issearch":0},{"name":"海草舞","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/12/15/7d0895d209af4713b48088a76d8b8f0a.jpg?m","ringid":"4055756","iscrbt":0,"synopsis":{"sinfo":"萧全-像一棵海草 随风飘摇 摇啊摇啊摇","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"c882faa896a1df727a200f55d14c7f2c","playtime":277,"islike":0,"likenum":45,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4055756","isvip":"0","songer":"萧全","isringing":1,"isbell":1,"ishot":"0","rtime":1520007715000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"0","list_name":"抖音热门","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/33/1518323303474.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1377","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/32/1518323290961.png","list_content":"抖音热门歌曲榜","total_count":110,"list_count":20},"hash":"","code":1,"msg":"","timespan":1541386442399}',

            '{"data":{"list":[{"name":"流浪","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/365.jpeg","ringid":"3885785","iscrbt":0,"synopsis":{"sinfo":"卢焱-我想出去走一走 看看这个大世界","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e4a4bc6d82653ab8132a3df15db866b4","playtime":200,"islike":0,"likenum":29,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3885785","isvip":"0","songer":"卢焱","isringing":1,"isbell":1,"ishot":"1","rtime":1510849530000,"issearch":0},{"name":"修炼","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/03/20/9e3a10dbbb4940a78eb1f8e2c1d4a574.jpg?m","ringid":"4380300","iscrbt":0,"synopsis":{"sinfo":"M哥-修炼爱情 也是修炼自己","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f218b1fd031f15d5d0b","playtime":206,"islike":0,"likenum":45,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4380300","isvip":"0","songer":"M哥","isringing":1,"isbell":1,"ishot":"0","rtime":1532061630000,"issearch":0},{"name":"远走高飞","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/6c67ab80d7a344f2a7e8295fbc82741a.jpg?m","ringid":"3575438","iscrbt":0,"synopsis":{"sinfo":"金志文-如果迎着风就飞俯瞰这世界多美","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4fec4a4164d526a4f0a02a405f149382","playtime":242,"islike":0,"likenum":28,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3575438","isvip":"0","songer":"金志文- 迎着风去追寻属于自己的梦吧","isringing":1,"isbell":1,"ishot":"1","rtime":1502354259000,"issearch":0},{"name":"可能否(抖音版)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/07/02/c193a1d62c394231953c3962aa8a5235.jpg?m","ringid":"4310674","iscrbt":0,"synopsis":{"sinfo":"木小雅-可能我撞了南墙才会回头吧","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fa720daeee693b8117d8b9e7f96dcea0","playtime":227,"islike":0,"likenum":522,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4310674","isvip":"0","songer":"木小雅","isringing":1,"isbell":1,"ishot":"0","rtime":1529606443000,"issearch":0},{"name":"完美的陌生人","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/08/17/3e556700e18146669184f9e4097c6ca5.jpg?m","ringid":"4440739","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"9085b1cf2e2a769bdc859e0f4f4170ee","playtime":274,"islike":0,"likenum":10,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4440739","isvip":"0","songer":"曲肖冰","isringing":1,"isbell":1,"ishot":"0","rtime":1533922992000,"issearch":0},{"name":"情人迷","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/52.jpeg","ringid":"4026797","iscrbt":0,"synopsis":{"sinfo":"王琪-抖音一更啊里月过花墙","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"266dab048bca0d78decb721523c21950","playtime":273,"islike":0,"likenum":18,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4026797","isvip":"0","songer":"王琪","isringing":1,"isbell":1,"ishot":"1","rtime":1518366015000,"issearch":0},{"name":"答案","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2017/04/26/1609222340437260.jpg?m","ringid":"2364391","iscrbt":0,"synopsis":{"sinfo":"郭采洁,杨坤-大叔加萝莉组合","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"dd01d29d745678833aba3d68593d5a78","playtime":231,"islike":0,"likenum":106,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=2364391","isvip":"0","songer":"郭采洁,杨坤","isringing":1,"isbell":1,"ishot":"1","rtime":1468241692000,"issearch":0},{"name":"卡布奇诺","type":1,"picpath":"http://open.migu.cn:8100/material/pics/artist/m/2018/03/20/9e3a10dbbb4940a78eb1f8e2c1d4a574.jpg?m","ringid":"4309452","iscrbt":0,"synopsis":{"sinfo":"M哥-抖音爆火M哥版","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f2143c8beda644282ac","playtime":150,"islike":0,"likenum":74,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4309452","isvip":"0","songer":"M哥","isringing":1,"isbell":1,"ishot":"0","rtime":1529516442000,"issearch":0},{"name":"往后余生(抖音最火版)","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/201.jpeg","ringid":"4338970","iscrbt":0,"synopsis":{"sinfo":"王贰浪-往后余生 我只要你","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a38d4e68883d825539d","playtime":215,"islike":0,"likenum":282,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4338970","isvip":"0","songer":"王贰浪","isringing":1,"isbell":1,"ishot":"1","rtime":1530891622000,"issearch":0},{"name":"浪人琵琶","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/26/dfad4d8fe345411693a4683b9215b0d6.jpg?m","ringid":"4321972","iscrbt":0,"synopsis":{"sinfo":"胡66-抖音爆红混搭风","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"cd66a41534cbe1b3b25ad3e61c3fe7ce","playtime":240,"islike":0,"likenum":208,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4321972","isvip":"0","songer":"胡66","isringing":1,"isbell":1,"ishot":"1","rtime":1530286871000,"issearch":0},{"name":"我的将军啊(DJ版)","type":1,"picpath":"https://imgzm.ringbox.cn/ringsetting/ringPic/1536/82/81/1536828185181.jpg","ringid":"4316573","iscrbt":0,"synopsis":{"sinfo":"戏语,韩子曦-抖音火爆动感BGM","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4f262dc2cf2a0c3651b6b95d8feaf66f","playtime":266,"islike":0,"likenum":58,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4316573","isvip":"0","songer":"戏语,韩子曦","isringing":1,"isbell":1,"ishot":"0","rtime":1530013212000,"issearch":0},{"name":"给陌生的你听","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/420.jpeg","ringid":"4312926","iscrbt":0,"synopsis":{"sinfo":"张思源-抖音热曲，陌生人你好","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"38ea3906856852d57bde6f1615c31a4b","playtime":196,"islike":0,"likenum":183,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4312926","isvip":"0","songer":"张思源","isringing":1,"isbell":1,"ishot":"0","rtime":1529692866000,"issearch":0},{"name":"云烟成雨(动画《我是江小白》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/10/ce0736eb2315437ea4cf9647e5794ef8.jpg?m","ringid":"4008278","iscrbt":0,"synopsis":{"sinfo":"房东的猫-抖音当红伤感文艺歌曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b3f8d8c8cd14137b833e40f9bb60cac8","playtime":240,"islike":0,"likenum":315,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4008278","isvip":"0","songer":"房东的猫","isringing":1,"isbell":1,"ishot":"1","rtime":1517761220000,"issearch":0},{"name":"往后余生","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/15/ca8a3b76c42c423c8612586330c4fb39.jpg?m","ringid":"4297113","iscrbt":0,"synopsis":{"sinfo":"马良-冬雪是你 春花是你 夏雨也是你","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2fb7f57a6437a38263a271eb41bd9ac","playtime":195,"islike":0,"likenum":300,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4297113","isvip":"0","songer":"马良","isringing":1,"isbell":1,"ishot":"1","rtime":1529145613000,"issearch":0},{"name":"嘴巴嘟嘟","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/29/ea9233288f074184992cd726ed02f77c.jpg?m","ringid":"4321321","iscrbt":0,"synopsis":{"sinfo":"刘子璇-你说嘴巴嘟嘟 嘟嘟嘟嘟嘟","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"6ba8c33eaf1da5467a4fc0378511ad86","playtime":248,"islike":0,"likenum":98,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4321321","isvip":"0","songer":"刘子璇","isringing":1,"isbell":1,"ishot":"1","rtime":1530279603000,"issearch":0},{"name":"纸短情长","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/22/8853c15ffbed450b98481d50b09902db.jpg?m","ringid":"4216097","iscrbt":0,"synopsis":{"sinfo":"花粥-爆红神曲粥总版","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d5af8833a3e50c899153aad9a68466da","playtime":186,"islike":0,"likenum":138,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4216097","isvip":"0","songer":"花粥","isringing":1,"isbell":1,"ishot":"1","rtime":1526665230000,"issearch":0},{"name":"卡布奇诺","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/92.jpeg","ringid":"4209746","iscrbt":0,"synopsis":{"sinfo":"6诗人-卡布奇诺的伤悲我无路可退","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f21af1bc797ccbe04ba","playtime":140,"islike":0,"likenum":97,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4209746","isvip":"0","songer":"6诗人","isringing":1,"isbell":1,"ishot":"1","rtime":1526470834000,"issearch":0},{"name":"青梅竹马","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/06/23/a48d8578aa8c4f4992e4bf4c28ea3b51.jpg?m","ringid":"4274259","iscrbt":0,"synopsis":{"sinfo":"陈秋含-抖音热门小清新","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e794ee2cf6636f213c14b3e909367903","playtime":242,"islike":0,"likenum":250,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4274259","isvip":"0","songer":"陈秋含","isringing":1,"isbell":1,"ishot":"0","rtime":1528357255000,"issearch":0},{"name":"9277 (原版)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/05/31/779b8be63b4d4ddda11fe74058ae9a7e.jpg?m","ringid":"4272270","iscrbt":0,"synopsis":{"sinfo":"宋孟君,威仔-要我怎么做怎么说才能爱上我","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e38e0289fe1207a3555f2c753ef5791e","playtime":186,"islike":0,"likenum":65,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4272270","isvip":"0","songer":"宋孟君,威仔","isringing":1,"isbell":1,"ishot":"0","rtime":1528270633000,"issearch":0},{"name":"心做し","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/01/13/00ad1cadf2e74c42a5f95edc92901b93.jpg?m","ringid":"3966097","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b5e38eb53a9a78d3211e0338fcb51412","playtime":276,"islike":0,"likenum":103,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=3966097","isvip":"0","songer":"Akane","isringing":1,"isbell":1,"ishot":"0","rtime":1515169237000,"issearch":0}],"list_editer":"莫忘尘","list_islike":"0","list_likenum":"0","list_name":"抖音热门","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/33/1518323303474.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1377","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1518/32/32/1518323290961.png","list_content":"抖音热门歌曲榜","total_count":110,"list_count":20},"hash":"","code":1,"msg":"","timespan":1541386439637}',
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
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=445&t=0%202&p=1&pn=80',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=368&t=0%202&p=1&pn=80',
            'http://api.ring.kugou.com/ring/ctgdetails?ctid=412&t=0%202&p=1&pn=80',
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