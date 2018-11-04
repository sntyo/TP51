<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Staff;//引入对应表的模型

class ModelEdu extends Controller//ModelEdu驼峰式写法在url中要转换成下换线式model_edu
{
    //获取器
    public function gain()
    {
        $res = Staff::get(2);  //实例并初始化Staff模型
//        \var_dump($res);  //查看所有字段信息
        echo $res->sex, '<br>';  //查看经过修改器处理后的性别字段
        echo $res->getData('sex'), '<br>'; //查看原始字段值
        echo $res->salary, '<br>'; ////查看经过修改器处理后的工资字段(为了保密加了200,非真实工资)
        echo  $res->staff_info, '<br>'; //获取虚拟字段
        //查看一个不存在的字段信息,体验获取器的强大之处
    }

    //修改器: 先给staff表新增一个字段entry_time: 入职时间
    public function modify()
    {
        $res = Staff::get(3);
        $res->entry_time = '2015-05-22';
//         $res->save();

        $res = Staff::get(3);
        $res->salary = 7800;
        $res->save();
        return '修改成功';
    }
//
    //自动完成:
    public function auto()
    {
//        Staff::create(['name'=>'灭绝师太','age'=>59, 'delete_time'=>10000, 'entry_time'=>1999]);
        Staff::update(['age'=>24], ['staff_id'=>37]);
    }
}
