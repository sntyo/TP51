<?php
namespace app\index\controller;
use think\Db;
//引入数据库入口类

/** tp中常用的几种数据库操作方法
            table（）方法指定要查询的数据表名称
 *          name（）方法指定要查询的表名 可以省略数据表前缀
 *          find（）查询单条数据 查询到所有符合条件的数据的第一个数据
*          where（）指定条件 参数是 （字段，表达式，条件）
*          field()指定字段 该方式传入的方式可以是字符串 数组
*          order() 指定条件排序方式 默认是从小到大 ASC 从大到小是 DESC 参数（'field'，'desc'）
*          limit() 指定返回的数据条数 参数传入的是 int
*          select() 最终方法 返回的是查询的多条数据 不建议传入参数
*          insert()插入单条数据 参数是字段组成的一维数组；
*          getLastInsID()返回最后插入的数据id 仅仅适用于单条查询
*          insertGetId() 执行插入数据并返回最后受影响的id
*          inserAll（）插入多条数据 参数是字段组成的二维数组
*          update（）危险操作 ，不允许无条件更新数据通常和where（）配合使用
*          date（）该方法用于储存数据 参数是数组
*          raw（）引用原始字段的值
*          delete（）危险操作 不允许无条件删除
*/

class Query
{
    //读操作返回都是二维数组,没有满足条件的记录则返回空数组
    //写操作返回受影响的记录数量,没有数据被改变返回0
    public function find()
    {
        //查询单条记录
        //table()指定要查询的完整表名,推荐使用
        //name()可以省略掉前缀,因为我的表没有前缀,也没有database.php中设置前缀,所以不用它
        //推荐数据表不要加前缀,也不要用name(),用table()完全满足要求
        //find()方法可以获取到满足条件的记录中的第一个,即只返回单条记录
        //如果是根据主键查询,可以直接将主键做为参数传入
        $res = Db::table('staff')->find(10);
        //从staff表中得到主键为10的记录

        //更多的时候,查询条件是通过where()方法传入,可以看到执行效果是一样的
        //where(字段名,表达式,查询条件),表达式为=号,可以省略,相等是默认值
        $res = Db::table('staff')
//             ->where('staff_id','=',11)//=是存在，不写默认存在
            // ->where('staff_id','>',11)//通过where设置查询条件，去ID>11的一个值
            ->where('staff_id',11)//通过where指定id
            ->find();
//
//        //如果要指定查询的字段使用field()方法
        $res = Db::table('staff')
//             ->field('name,sex,salary')//指定三个字段名
             ->field(['name','sex','age']) //参数可使用数组 数组中的每一个元素对应一个字段名
//            ->field(['name'=>'姓名','sex'=>'性别','age'=>'年龄']) //可设置字段别名，相当AS关键字
            ->where('staff_id',11)
            ->find();
        dump($res);
    }


    public function select()
    {
        //查询满足条件的多条记录select()
        $res = Db::table('staff')
            ->field(['name','salary'])
//            ->field(['name'=>'姓名','salary'=>'工资'])
            //where()支持直接传入字符串为查询条件
//             ->where('salary > 3000') //工资大于3000
            //where()使用表达式参数
            ->where('salary','>',3000)
            //where(字段名,表达式,查询条件)
            //order()支持字符串
            // ->order('salary DESC')
            //order()也支持数组
            ->order('salary','DESC')
            //AESC升序，默认ASEC
            ->limit(5)
            ->select();

        dump($res);
    }

    public function insert()
    {
        //新增单条记录 insert()
        //新增数据不需要进行前置查询操作

        //准备要插入的数据:以关联数组的形式
        $data = [
            'name' => '胡一刀',
            'sex' => 0,
            'age' => 49,
            'salary' => 5300
        ];
        $num = Db::table('staff')->insert($data);
        $id = Db::getLastInsID();//此方法返回新增主键id
        return $num ? '添加成功,id='.$id : '没有记录被添加';
        //不能重复添加，否则会报错

        //如果想新增成功后,直接返回新记录的主键id,就是把上面二步变成一步操作
        //data($data): 将要处理的数据打包 option[]
        //insertGetId() == insert() + getLastInsID()
        //可以使用: insertGetId()方法代替 insert()
        //记得将上面的执行语句注释掉

        //insertGetId($data)二合一写法
        $id = Db::table('staff')->insertGetId($data);
        return $id ? '添加成功,id='.$id : '没有记录被添加';

//        //推荐使用data()方法将要新增的记录进行打包,尽量不要在最终方法中传入参数
        $num = Db::table('staff')->data($data)->insert();
        $id = Db::getLastInsID();
        return $num ? '添加成功,id='.$id : '没有记录被添加';

        //不建议在find() select()中写参数，因为它们是最终方法&终结方法，链式方式中的结尾
        //尽可能把参数放在前面处理，比如data()

        //新增多条记录 insertAll(),语法与新增单条基本一致
        //新增多条记录,返回新增记录的数量
        $data = [
            ['name' => '张飞','sex' => 0,'age' => 48,'salary' => 6900],
            ['name' => '刘备','sex' => 0,'age' => 58,'salary' => 4500],
            ['name' => '关羽','sex' => 0,'age' => 53,'salary' => 4700],
        ];
        $num = Db::table('staff')->data($data)->insertAll();
        return $num ? '添加成功'.$num.'条记录~~' : '没有记录被添加';

    }


    public function update()
    {
        //更新操作必须是基于前置查询,不允许无条件更新
        //更新操作使用的是update()方法

        //例如:将工资小于等于4000的,加薪1000
        $num = Db::table('staff')
            ->where('salary','<=',5000)
            //这里要引用原salary字段的值,所以要用到Db::raw()引用原始数据
            ->data(['salary'=> Db::raw('salary+1000')])
            ->update();


        $num = Db::table('staff')
            //如果更新记录中存在主键,则直接根据主键更新
            ->update(['sex'=>0,'staff_id'=>16]);
        return $num ? '更新成功'.$num.'条记录~~' : '没有记录被更新';
    }

    public function delete()
    {
        //删除也更新操作一样,也必须是基于前置查询,绝不允许无条件删除
        //删除操作使用:delete()方法

        // $num = Db::table('staff')->delete(19);//删除id=19
        // $num = Db::table('staff')->delete([12,14,18]);//多个主键使用数组传入
        $num = Db::table('staff')->where('salary','>',10000)->delete();

        //如果想删除全部记录,可直接给delete()方法传入true: delete(true)
        $num = Db::table('staff')->delete(true);  //数据表后面还要用,此功能课后练习

        return $num ? '删除成功'.$num.'条记录~~' : '没有记录被删除';

        //提醒: 删除数据是非常危险的操作,强烈建议使用框架提供的软删除来实现,即用更新来模拟删除
    }
}

//提示: 在实际开发过程中,尽可能避免直接在控制器进行数据库操作,而是使用模型来实现




















