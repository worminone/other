<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    
        //开发环境，测试环境还是生产环境
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],
    
    'url_route_on'   => true,
   
    'URL_MODEL'=>2,
    // 'log'          => [
    //     'type' => 'trace',
    // ],
    'show_error_msg' => true,

    // 'default_return_type'=>'json',

    //本项目的配置
    "web_res_root"   => "/static/",
    "web_root"       => "/index.php/",
    'url_route_on' => true,
    'session' => [
        'auto_start' => true,
        // 'name' => 'login@',
        'expire' => 1800,                        /*时间长度*/
    ],

    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/public/',
        '__ROOT__' => '/',
    ],

    'captcha'  => [
        // 验证码字符集合
        // 'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY', 
        // 验证码字体大小(px)
        'fontSize' => 30, 
        // 是否画混淆曲线
        'useCurve' => false, 
         // 验证码图片高度
        'imageH'   => 50,
        // 验证码图片宽度
        'imageW'   => 200, 
        // 验证码位数
        'length'   => 4, 
        // 验证成功后是否重置        
        'reset'    => true
    ],


    // // | 缓存设置
    // 'cache' => [// 使用复合缓存类型
    //     'type'    => 'complex', // 默认使用的缓存
    //     'default' =>
    //         [// 驱动方式
    //             'type' => 'Redis', // 缓存保存目录
    //             'path' => CACHE_PATH,
    //         ], // 文件缓存
    //     'file'    =>
    //         [// 驱动方式
    //             'type'   => 'file', // 设置不同的缓存保存目录
    //             'path'   => RUNTIME_PATH . 'file/', // 缓存有效期 0表示永久缓存
    //             'expire' => 0,
    //         ], // redis缓存
    //     'redis'   =>
    //         [// 驱动方式
    //             'type'     => 'Redis', //
    //             'port'     => 6379, // 服务器地址
    //             'host'     => '127.0.0.1', // 缓存有效期 0表示永久缓存
    //          //   'password' => '',                // redis 密码
    //             'expire'   => 1800
    //         ],
    // ],

];
