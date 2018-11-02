<?php
namespace app\index\controller;

use think\facade\Config;
class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5', $course = 'sn')
    {
        return 'hello,' . $name.'&&&&'.$course;
    }

    public function con_get()
    {
        //1. 使用Config类
        //获取全部配置项
        //dump(Config::get());

        //仅获取某一个一级配置项: qpp
//        dump(Config::get('app.'));
//        dump(Config::pull('app'));//pull后不要加.
//          dump(Config::has('default_lang'));//默认app.php配置文件中查找
//          dump(Config::has('app.default_lang'));
//          dump(Config::get('app.default_lang'));//先查寻获，后获取
          //使用Config类的静态函数

        //2.助手函数config(),不需要导入配置类
//        dump(config());//获取到了全部配置项
//        dump(config('database.'));//获取database.php一级配置项
//        dump(config('?default_lang'));
//        dump(config('default_lang'));

        //3. 动态配置
//          Config::set('app.admin_email', 'sntyo@yhsy.cn');
//          dump(Config::pull('app'));//查看app.php是否有此动态配置项
//          dump(Config::get('app.admin_email'));
//          return (Config::get('app.admin_email'));

    }
}
