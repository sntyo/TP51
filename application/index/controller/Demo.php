<?php

namespace app\index\controller;
use think\Db;

class Demo
{
    public function select()
    {
        //测试是否连接成功
         dump(Db::query("select * from staff"));
         //使用Db的命名空间
         dump(\think\Db::query("select * from staff"));
        //在代码中直接写sql语句就是原生查询

        //原生查询: 只用到连接器类Connection中的query()读操作 SELECT
        // execute()专用于写操作 CUD

        //查询staff表中,salary大于5000的员工信息
        //使用通用占位符?  防止sql注入攻击。在sql语句执行的时候再传递参数
        $sql = "SELECT name AS 姓名,salary AS 工资 FROM staff WHERE salary>? LIMIT ?;";
//        //参数绑定:参数由索引数组表示,元素顺序与占位符顺序必须一一对应
//        //与顺序绑定，不灵活
//
        $res = Db::query($sql,[5000,3]);
//        //查看查询结果:自动将结果解析为二维数组输出
        dump($res);
        //dsn: mysql:host=localhost;port=8889;dbname=php;charset=utf8
        //使用命名占位符: 用关联数组进行表示
        $sql = "SELECT name AS 姓名,salary AS 工资 FROM staff WHERE salary>:salary LIMIT :num ;";
        //参数绑定:参数由关联数组表示,键名与命名占位符一致,顺序无所谓
        $res = Db::query($sql,['num'=>5,'salary'=>5000]);
        dump($res);

        //跟踪器中查看生成的SQL语句,发现参数的类型并不匹配,因为参数默认为字符型,需要转换为数值
        //只需要修改一下参数绑定语句,给参数值传递第二个参数:PDO参数常量,进行类型限定
        $res = Db::query($sql,['num'=>[5, \PDO::PARAM_INT],'salary'=>[5000,\PDO::PARAM_INT]]);
        dump($res);
    }

    public function update()
    {
        //将id=10的员工,工资修改为6500
        $sql = "UPDATE staff SET salary = :salary WHERE staff_id = :staff_id ;";

        Db::execute($sql,['salary'=>[6500,\PDO::PARAM_INT], 'staff_id'=>[10, \PDO::PARAM_INT]]);
        //如果失败会自动中止运行,抛出异常
        return '更新成功';
    }
    //在url通过update()来调用  http://tp51:8889/think/public/index.php/index/demo/update
}