<?php
namespace app\index\controller;
use think\facade\View;//引入视图类静态代理: 将内部方法全部看作静态进行调用
use think\Controller;


class Index extends Controller
{
    public function index()
    {
        return '<h3>欢迎来到PHP中文网学习<span style="color:red">ThinkPHP5.1</span>框架开发</h3>';
    }

    //模板渲染
    public function demo1()
    {
        $name = 'peter';
        //直接输出变量
//        return $name;
        //使用视图方法:display()不通过模板,直接渲染内容,支持HTML标签
//         return View::display($name);//使用视图类display()来渲染内容
//         return View::display('我的姓名是:'.$name);
//         return View::display('我的姓名是:<span style="color:red">'.$name.'</span>');

        //以上方法输出模板内容,内容不多还行,如果内容很多,就不是很方便了
        //建议使用fetch(),加载指定模板文件输出
        //fetch(模板表达式)指定一个模板进行内容输出
        //模板表达式: 模块@控制器/操作方法
        //模板默认根目录是:模块/view/控制器名/模板文件名
        //模板以模块/view目录为跟目录
        //模板文件的后缀可自定义config/template.php,默认为html
        //fetch('模板表达式',[模板变量数组],[模板配置数组])
        //模板参数我们一般不在控制器动态配置,而是在config/template.php中统一配置

        //除了直接导入View门面类进行调用之外,其实控制器Controller类封装模板中的常用方法
        //只要当前控制器类继承了Controller,就可以直接使用这些内置方法
        //控制器类中有一个属性view,保存着视图类的实例对象
//         return View::fetch('demo1',['name'=>'peter']);
//         return $this->view->fetch('demo1',['name'=>'peter']);
        return View::fetch('index@index/demo1',['name'=>$name]);
        //控制类Controller.php中对常用视图方法做了简化
        // return $this->fetch('demo1',['name'=>'peter']);

        //框架也内置了一个助手函数提供大家使用view()
        //在任何情况下都可以使用助手函数,不依赖外部是否导入了某个类
        //可以把前面的use导入的所有类全部注释掉
//        return view('demo1',['name'=>'peter']);

    }

    //模板赋值
    public function demo2()
    {
        //模板赋值:
        //1. assign()
        //使用assign()必须要调用View类,以后我们统一使用Controller来调用
        // $name = 'peter';
        // $this->view->assign('name', $name);

        //2.传参方式: fetch()或view()刚才已经演示过了
        //3.对象方式:底是通过Controller中的二个魔术方法来实现视图类的数据注入的
        $this->view->name = 'peter';

        // return $this->fetch('demo2');

        //如果是按默认规则创建的模板文件,则模板文件名可以省略
        return $this->view->fetch();
    }

    //模板过滤与替换
    public function demo3()
    {
        //tp51也之前版本相比,直接删除了模板字符串替换功能,而改为模板配置参数来实现
        //config/template.php: 'tpl_replace_str' => [''=>''],
        //不过,我建议大家在控制器使用filter()直接进行过滤替换,更简洁
        //将模板中的peter zhu换成:朱老师
        $this->view->assign('name','peter');
        $filter = function($content) {
            return str_replace('peter', '朱老师', $content);
        };
        return $this->filter($filter)->fetch();
    }

    //模板布局
    public function demo4()
    {
        /**
         * 一、全局配置,先开启模板布局功能
         * 1. config/template.php
         * 2.'layout_on' => true, 'layout_name' ] = 'layout','layout_item'=>'{__REP__}'
         *
         *二、模板标签的方式
         * 1. 不需要在config/template.php中开启和配置布局模板
         * 2. 直接在需要使用布局模板的模板文件顶部指定布局模板文件即可: {layout name="layout" /}
         *
         * 二、在控制器动态配置
         * 1.不需要在模板配置文件做任何配置,也不需要在当前模板中添加任何标签
         * 2.当然,布局模板,还是要事先制作好的
         * 3.直接调用:$this->view-engine->layout(true)开启
         */

        // return $this->view->fetch();

        //动态开启模板布局,一切使用默认的布局参数:layout_name=layout,{__CONTENT__}
        // $this->view->engine->layout(true);
        // return $this->view->fetch();

        //能否将上面二条语句进行合并,进行链式调用呢?
        // return $this->view->engine->layout(true)->fetch();

        //必须给出完整的模板表达式:
        // return $this->view->engine->layout(true)->fetch('index@index/demo4');
        //其实@前面的模块名称是可以省略的
        // return $this->view->engine->layout(true)->fetch('index/demo4');

        //关闭模板布局
        // return $this->view->engine->layout(false)->fetch('index/demo4');

        //自定义模板布局参数,layout可以是任意的布局文件,被替换的内容标识符也可以自定义
        return $this->view->engine->layout('layout','{__TEXT__}')->fetch('index/demo4');
    }

    //模板继承
    public function demo5()
    {
        /**
         * 1.在view/base.html: 做为被其它模板继承的基础模板文件
         * 2.base.html中,需要被子模板重写的内容全部用{block name=""}进行定义
         * 3.子模板中直接用{extend name="" /}继承即可
         */

        return $this->view->fetch();
    }




}
