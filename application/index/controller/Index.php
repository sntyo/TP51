<?php
namespace app\index\controller;

include 'Demo.php';

class Index
{
    public function index()
    {
//        $demo = new \Demo();
//        return  $demo->test();
        var_dump($str='我是全局函数');
    }
}


//在本类中声明一个类Demo
//class Demo
//{
//    public function test()
//    {
//        return '我是app\index\controller空间中的Demo类';
//    }
//}
//类中是不能包含类的，也就是说不能把class Demo放到class Index中

//function var_dump()
//{
//    echo '我是当前空间中的函数var_dump()';
//}












