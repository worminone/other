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
            '{"data":{"list":[{"name":"沙漠骆驼","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/29/8fb9e6a4889e4ff8a17ada18e6af7902.jpg?m","ringid":"4734157","iscrbt":0,"synopsis":{"sinfo":"展展与罗罗-什么鬼魅传说 什么魑魅魍魉妖魔","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"9085b1cf2e2a769b224eba4a7372f32b","playtime":338,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4734157","isvip":"0","songer":"展展与罗罗","isringing":1,"isbell":1,"ishot":"1","rtime":1540446994000,"issearch":0},{"name":"精彩才刚刚开始","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/14/87dd8039cf3d45fea32b5e8be8060199.jpg?m","ringid":"4694721","iscrbt":0,"synopsis":{"sinfo":"易烊千玺-更精彩的未来 由此开始","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fe9df16f91261e1889cf0faa543ad215","playtime":227,"islike":0,"likenum":13,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4694721","isvip":"0","songer":"易烊千玺","isringing":1,"isbell":1,"ishot":"0","rtime":1539539701000,"issearch":0},{"name":"年少有为","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/16/2a71109b3cdd4127a46b5d99589673ac.jpg?m","ringid":"4375364","iscrbt":0,"synopsis":{"sinfo":"李荣浩-假如我年少有为 知进退","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"87296dd7d81e596430c46a655d329b42","playtime":279,"islike":0,"likenum":88,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4375364","isvip":"0","songer":"李荣浩","isringing":1,"isbell":1,"ishot":"1","rtime":1531935605000,"issearch":0},{"name":"可它爱着这个世界","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/29/72b462e4dd4d4c23b31b5a56e493d8a6.jpg?m","ringid":"4602820","iscrbt":0,"synopsis":{"sinfo":"周深-愿你能被这个世界 温柔以待","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e0ac0f2121bd067c9b628ecf2f5bc527","playtime":216,"islike":0,"likenum":18,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4602820","isvip":"0","songer":"周深","isringing":1,"isbell":1,"ishot":"0","rtime":1538218564000,"issearch":0},{"name":"那一夜","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/17/5f44a11d36d445e7a1b4d804b52b1631.jpg?m","ringid":"4712796","iscrbt":0,"synopsis":{"sinfo":"G.E.M.邓紫棋-我沉睡的躯壳 有你重新活着","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7cfb5259969543c9cb1c7e0f1297fc21","playtime":234,"islike":0,"likenum":6,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4712796","isvip":"0","songer":"G.E.M.邓紫棋","isringing":1,"isbell":1,"ishot":"0","rtime":1539798964000,"issearch":0},{"name":"破坏王","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/18/36e7f070ff464142be2e77b7404a0691.jpg?m","ringid":"4717318","iscrbt":0,"synopsis":{"sinfo":"陈奕迅-撇开深情 最佻皮的Eason正式上线！","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5ba5f0b0dbe935e9beca227ab6aebd01","playtime":259,"islike":0,"likenum":3,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4717318","isvip":"0","songer":"陈奕迅","isringing":1,"isbell":1,"ishot":"0","rtime":1539863690000,"issearch":0},{"name":"永夜(网络剧《将夜》推广曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/25/dd3bd85bc0014ed583d3ec98b27ffd00.jpg?m","ringid":"4741075","iscrbt":0,"synopsis":{"sinfo":"谭维维-或许前路永夜 我们也要前进","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"465319747d000770a8423cd4b77b0c22","playtime":254,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4741075","isvip":"0","songer":"谭维维","isringing":1,"isbell":1,"ishot":"0","rtime":1540529926000,"issearch":0},{"name":"Waste It On Me","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/25/21bd39d3295941358ea4d89ee733ac10.jpg?m","ringid":"4735862","iscrbt":0,"synopsis":{"sinfo":"","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7e6ca9195d4ce63385b75cf6f2586417","playtime":192,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4735862","isvip":"0","songer":"Steve Aoki,防弹少年团","isringing":1,"isbell":1,"ishot":"0","rtime":1540479279000,"issearch":0},{"name":"影响(电视剧《创业时代》爱情主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/18/08d643c9f8b04af4b355fc950d794f42.jpg?m","ringid":"4713973","iscrbt":0,"synopsis":{"sinfo":"陈粒-我假装不在乎悲伤 却眼含泪光","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5dbca69e8ed2b167b96","playtime":225,"islike":0,"likenum":7,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4713973","isvip":"0","songer":"陈粒","isringing":1,"isbell":1,"ishot":"0","rtime":1539827781000,"issearch":0},{"name":"小了白了兔","type":1,"picpath":"http://imgzm.ringbox.com.cn/ringsetting/ringPic/random/362.jpeg","ringid":"4646772","iscrbt":0,"synopsis":{"sinfo":"陈皮皮,吴欢-洗脑神曲 小了白了兔 白了又了白","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4985564f2f854dc47f443665509e33b4","playtime":120,"islike":0,"likenum":9,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4646772","isvip":"0","songer":"陈皮皮,吴欢","isringing":1,"isbell":1,"ishot":"1","rtime":1538902570000,"issearch":0},{"name":"张家明和婉君","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/16/ba181a4787bf403e950f804af11e232b.jpg?m","ringid":"4592847","iscrbt":0,"synopsis":{"sinfo":"李荣浩-心动单曲 那个似曾相识的男孩和女孩","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"87296dd7d81e59643fac5e7921ba3280","playtime":246,"islike":0,"likenum":5,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4592847","isvip":"0","songer":"李荣浩","isringing":1,"isbell":1,"ishot":"0","rtime":1538056527000,"issearch":0},{"name":"流浪","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/28/edefb413c7364fc1904bfae30f99bd15.jpg?m","ringid":"4632600","iscrbt":0,"synopsis":{"sinfo":"半阳-人生走的太漫长 流浪到岁月枯黄","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"9085b1cf2e2a769b164d3e8eadbdb9fb","playtime":219,"islike":0,"likenum":39,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4632600","isvip":"0","songer":"半阳","isringing":1,"isbell":1,"ishot":"0","rtime":1538751346000,"issearch":0},{"name":"悟","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/12/887a20a8cba249bca10a34b08fd6a292.jpg?m","ringid":"4682516","iscrbt":0,"synopsis":{"sinfo":"吴亦凡-我活着就是为了看看天有多高","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"5ba5f0b0dbe935e999addd6e7b2516fd","playtime":212,"islike":0,"likenum":7,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4682516","isvip":"0","songer":"吴亦凡","isringing":1,"isbell":1,"ishot":"0","rtime":1539334462000,"issearch":0},{"name":"从无到有(电视剧《创业时代》片尾主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/08/48c71fa7bea1413e8476ffce3e247b9d.jpg?m","ringid":"4652908","iscrbt":0,"synopsis":{"sinfo":"毛不易-今生只有你看好我 只有你相信我有梦","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5dba0f3ea8b66241e73","playtime":277,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4652908","isvip":"0","songer":"毛不易","isringing":1,"isbell":1,"ishot":"0","rtime":1538996266000,"issearch":0},{"name":"恰好(电影《功夫联盟》主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/21/18f110e63dd547ac817f959a63eb0773.jpg?m","ringid":"4724741","iscrbt":0,"synopsis":{"sinfo":"A-Lin-最美的安排也许是没有安排 一切都是恰好","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"7e6ca9195d4ce633c80fdc2207e12ab5","playtime":255,"islike":0,"likenum":3,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4724741","isvip":"0","songer":"A-Lin","isringing":1,"isbell":1,"ishot":"0","rtime":1540144550000,"issearch":0},{"name":"蓝色眼睛(电视剧《我的波塞冬》插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/23/122c086f7f654aca928c8c742a484d15.jpg?m","ringid":"4725305","iscrbt":0,"synopsis":{"sinfo":"阿冷-人气歌手创新演绎都市轻奇幻剧插曲","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a1918d909d36bd0c7dab443f4a2f9e73","playtime":208,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4725305","isvip":"0","songer":"阿冷","isringing":1,"isbell":1,"ishot":"0","rtime":1540270682000,"issearch":0},{"name":"不及雨","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/27/7a4de673b7e842f198edf3134e031325.jpg?m","ringid":"4610177","iscrbt":0,"synopsis":{"sinfo":"张碧晨-不曾忘为什么出发 才会有更好的到达","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"b7b58ee3bbe5cb5ae31f0eff28adb5ac","playtime":253,"islike":0,"likenum":7,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4610177","isvip":"0","songer":"张碧晨","isringing":1,"isbell":1,"ishot":"0","rtime":1538330136000,"issearch":0},{"name":"空","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/10/338ab3c480764344bd4e9cb18d8b0f08.jpg?m","ringid":"4674238","iscrbt":0,"synopsis":{"sinfo":"谢霆锋-放眼过世界 才懂欣赏眼前","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d400e29a827041713ed11219e81ceaf8","playtime":254,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4674238","isvip":"0","songer":"谢霆锋","isringing":1,"isbell":1,"ishot":"0","rtime":1539194105000,"issearch":0},{"name":"太早","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/24/236f7bd2bf0d4453a356d0e7c379e565.jpg?m","ringid":"4731002","iscrbt":0,"synopsis":{"sinfo":"庄心妍-我们爱的是不是太早 终将不能到老","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4cbcc1b5f4ca08ca09479aec527da990","playtime":239,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4731002","isvip":"0","songer":"庄心妍","isringing":1,"isbell":1,"ishot":"0","rtime":1540386094000,"issearch":0},{"name":"冷风吹","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/20/9702091459cb4463865227a315151125.jpg?m","ringid":"4724237","iscrbt":0,"synopsis":{"sinfo":"李袁杰-冷风吹乱你的头发 却吹不散你对他的牵挂","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"a20a79263b86e8a8605723a853247239","playtime":210,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4724237","isvip":"0","songer":"李袁杰","isringing":1,"isbell":1,"ishot":"0","rtime":1540058162000,"issearch":0}],"list_editer":"彼岸忘川爱荼蘼","list_islike":"0","list_likenum":"3","list_name":"10月新热铃声合辑","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1540/79/79/1540797946321.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1540","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1540/79/79/1540797958542.png","list_content":"10月新热铃声合辑","total_count":30,"list_count":20},"hash":"","code":1,"msg":"","timespan":1541044068041}',
            '{"data":{"list":[{"name":"给妈妈（电影《悲伤逆流成河》插曲）","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/09/27/ba3c64967ccc40e4ab61401d12657c00.jpg?m","ringid":"4610208","iscrbt":0,"synopsis":{"sinfo":"房东的猫-上帝无法无处不在 所以创造了妈妈","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fb9941aae8c7af94be116b56f79153eb","playtime":263,"islike":0,"likenum":3,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4610208","isvip":"0","songer":"房东的猫","isringing":1,"isbell":1,"ishot":"0","rtime":1538330144000,"issearch":0},{"name":"幻夜(网络剧《盛唐幻夜》插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/16/cf63edeb4c1a473eb6c3cd6cf5b1dd01.jpg?m","ringid":"4708721","iscrbt":0,"synopsis":{"sinfo":"金志文-幻夜流光如尘 爱是两颗心闯奇门","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fe7480e1a94a9259317e27573ccd1136","playtime":343,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4708721","isvip":"0","songer":"金志文","isringing":1,"isbell":1,"ishot":"0","rtime":1539744859000,"issearch":0},{"name":"无名之辈(电影《无名之辈》同名主题曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/22/530d0350049d4a40b2d7802a3acc1fa9.jpg?m","ringid":"4725283","iscrbt":0,"synopsis":{"sinfo":"汪苏泷-也许不是世界的主角 但也不做人生的鸡肋","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"d685cd691b76f5db7ea345872e6ce0a2","playtime":216,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4725283","isvip":"0","songer":"汪苏泷","isringing":1,"isbell":1,"ishot":"0","rtime":1540270681000,"issearch":0},{"name":"如果没有遇到你(偶像网剧《美丽见习生》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/23/bb55c91b86dc4e4d98f3ae834de9a25c.jpg?m","ringid":"4724993","iscrbt":0,"synopsis":{"sinfo":"曲肖冰-感谢你的出现 因为爱过 不曾后悔","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"e38e0289fe1207a3982cd17d35147dbc","playtime":237,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4724993","isvip":"0","songer":"曲肖冰","isringing":1,"isbell":1,"ishot":"0","rtime":1540263348000,"issearch":0},{"name":"爱情宗师(电影《功夫联盟》片尾曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/15/502a4bfed2c443358aa0a58bbb3aaa0b.jpg?m","ringid":"4706457","iscrbt":0,"synopsis":{"sinfo":"李紫婷-我孤身行走江湖 见怪不怪","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fb9941aae8c7af94e31f0eff28adb5ac","playtime":216,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4706457","isvip":"0","songer":"李紫婷","isringing":1,"isbell":1,"ishot":"0","rtime":1539701673000,"issearch":0},{"name":"别惹女孩(电影《我的间谍前男友》推广曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/17/aeff33b06f6e4c7f9b56a34601a87e6f.jpg?m","ringid":"4711417","iscrbt":0,"synopsis":{"sinfo":"Yamy-别惹女孩 特别是Yamy这样的嘻哈少女","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"2d9ea9919a4dcf9e9a7cd49d87b5b70f","playtime":188,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4711417","isvip":"0","songer":"Yamy","isringing":1,"isbell":1,"ishot":"0","rtime":1539781020000,"issearch":0},{"name":"不想睡的猫","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/24/236f7bd2bf0d4453a356d0e7c379e565.jpg?m","ringid":"4730814","iscrbt":0,"synopsis":{"sinfo":"庄心妍-站在食物链顶端的猫 就是任性","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"4cbcc1b5f4ca08ca6564ce1258640956","playtime":183,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4730814","isvip":"0","songer":"庄心妍","isringing":1,"isbell":1,"ishot":"0","rtime":1540382594000,"issearch":0},{"name":"无情画(网络剧《双世宠妃》第二季片头曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/12/19bd2a0503e140c49f7a530266e5652a.jpg?m","ringid":"4681224","iscrbt":0,"synopsis":{"sinfo":"王呈章-昨夜心头牵挂 今朝眼底繁花","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"87624cb25943ff21b25ad3e61c3fe7ce","playtime":255,"islike":0,"likenum":2,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4681224","isvip":"0","songer":"王呈章","isringing":1,"isbell":1,"ishot":"0","rtime":1539316500000,"issearch":0},{"name":"我喜欢你","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/11/b162bda3d5e74065ad6a846dd9e03088.jpg?m","ringid":"4678826","iscrbt":0,"synopsis":{"sinfo":"陈意涵-我想告诉你 我喜欢你","stype":"1","surltype":""},"isnew":"1","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"f2e3ae31e223c8cb32d87df1877382ee","playtime":251,"islike":0,"likenum":0,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4678826","isvip":"0","songer":"陈意涵","isringing":1,"isbell":1,"ishot":"0","rtime":1539262506000,"issearch":0},{"name":"拜托拜托(《双世宠妃2》网络剧插曲)","type":1,"picpath":"http://open.migu.cn:8100/material/pics/album/m/2018/10/23/931f3d16f15842afac66d555be68e310.jpg?m","ringid":"4730634","iscrbt":0,"synopsis":{"sinfo":"梁洁-古风魔性节奏 甜宠风高调来袭","stype":"1","surltype":""},"isnew":"0","coprinfo":{"type":"99","price":"200","cflag":"2001","promote":""},"springid":"fe7480e1a94a9259779a12df5453a3e4","playtime":187,"islike":0,"likenum":1,"shareurl":"https://m.ringbox.cn/m/appshare/daypush,00000590521.html?ringid=4730634","isvip":"0","songer":"梁洁","isringing":1,"isbell":1,"ishot":"0","rtime":1540378992000,"issearch":0}],"list_editer":"彼岸忘川爱荼蘼","list_islike":"0","list_likenum":"3","list_name":"10月新热铃声合辑","list_pic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1540/79/79/1540797946321.png","list_url":"https://m.ringbox.cn/m/appshare/parts,00000590521.html?pid=1540","list_urlpic":"https://imgzm.ringbox.cn/ringsetting/crbtpic/1540/79/79/1540797958542.png","list_content":"10月新热铃声合辑","total_count":30,"list_count":10},"hash":"","code":1,"msg":"","timespan":1541044306820}',
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