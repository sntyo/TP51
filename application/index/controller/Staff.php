<?php
namespace app\index\controller;

//引用控制器类
use think\Controller;

//导入模型
use app\index\model\Staff as StaffModel; //设置别名,防止与当前控制器冲突

class Staff extends Controller
{
    // public function show()
    //实例化模型
    public function instance(StaffModel $staff) //依赖注入
    {

        // $staff = new StaffModel();	//模型实例化
        // 通过模型实例化把引入的Model通过new的方式生成实例对象并保存在变量$staff中
        // getName() 获取当前模型名称
        // dump($staff->getName()); //查看模型名称

        //新增一条记录
        $staff->name = 'peter';
        $staff->sex = 0;
        $staff->age = 18;
        $staff->salary = 4800;
        $staff->save();//新增操作
        return '新增成功,id='.$staff->staff_id;

        //实际工作中,我们几乎全部采用模型的静态方法调用来实现数据操作
    }

    //模型查询
    public function query()
    {
        //1.单条记录:静态get(主键/闭包)方法
        //闭包就是一个匿名回调函数
        //此时完成了二个操作:1.创建模型对象 2. 模型对象初始化(赋值)
        $staff = StaffModel::get(2);  //以静态查询方式创建模型对象

        dump($staff);  //dump()是框架内置调试函数,对数据做了预处理,返回数组
        \var_dump($staff);  //全局var_dump()才能看到原始数据类型:Object

        //所以查看某一个字段的值,即可以用数组方式,也可以用对象方式
        echo $staff['name'],'<br>';  //数组方式
        echo $staff->salary,'<br>';  //对象方式
        echo '<hr>';

        //如果查询条件复杂可以使用闭包方式创建查询条件
        $staff = StaffModel::get(function($query){
            //$query为查询对象,可以任意设定
            $query->where('sex',0)->where('salary','>',8000);
            //where()可以连续调用
        });
        //get()只返回满足条件的第一个
        echo '性别为男,工资大于8000的员工信息:<br>';
        dump($staff);
        echo '<hr>';

        //也可以直接调用Db类的查询构造器来进行查询
        //模型可以静态调用所有的查询构造器方法
        echo '年龄大于50员工信息:';
        $staff = StaffModel::field('name,salary')->where('age','>',50)->find();
        dump($staff);
        echo '<hr>';

        //2.多条记录查询: all(主键列表/闭包)
        //返回值是一个多维数组/对象数组
        $staffs = StaffModel::all(); //获取所有员工信息
        //Model类中all() 相当于 SELECT * FROM `staff`
        dump($staffs);
        echo '<hr>';
        $staffs = StaffModel::all([1,2,3]); //返回主键=1,2,3的记录
        //SELECT * FROM `staff` WHERE `staff_id` IN (1,2,3)
        dump($staffs);
        echo '<hr>';
        //all()也支持闭包查询,这也是我们以后常用的方式
        $staffs = StaffModel::all(function($query){
            $query->where('age','<=',40)->where('salary','>',3000);
        });
        // SELECT * FROM `staff` WHERE `age` <= 40 AND `salary` > 3000
        dump($staffs);
        echo '<hr>';

        //all()返回的数组,我们一般是采用循环的方式进行遍历
        foreach ($staffs as $staff) {
            echo '姓名:'.$staff->name.'<br>';
            echo '年龄:'.$staff->age.'<br>';
            echo '工资:'.$staff->salary.'<hr>';
        }

        echo '<hr color="red">';
        //参数在URL中以请求变量的形式传入 staff/query/age/40/salary/6000
        //采用闭包来实现将请求变量注入到闭包条件中
        //采用闭包的好处非常多,特别是支持查询变量从外部传入
        //举例,查询条件由用户通过URL请求提供
        //控制器提供一个属性$request,其值就是请求对象,可用来快速请求变量
        //如果当前请求变量中存在age和salary由获取到,否则使用默认值40,3000
        //$this-request, request是当前控制器属性，值就是请求对象，请求变量
        //$this->request =  new \thinl\facade\Request
        $age = $this->request->param('age') ?: 40;
        $salary = $this->request->param('salary') ?: 3000;
        //?: 存在用自己本身的值，不存在用默认值
        //param 	获取当前请求的变量

        $staffs = StaffModel::all(function($query) use ($age, $salary){
            $query->where('age','<=',$age)->where('salary','>',$salary);
        });
        //通过use引入外部的变量
        dump($staffs);
        //实现动态的查询 URL:  query/age/30/salary/4000
        //相当于: SELECT * FROM `staff` WHERE `age` <= 30 AND `salary` > 4000
    }

    //模型更新
    public function update()
    {
        //更新必须是基于查询的,不允许无条件更新

        //最简单直观的方式是先查询,再模型调用save()
        $staff = StaffModel::get(2); //查询获取到要更新的记录
        $staff->name = '龙姑娘';  //更新记录字段
        $staff->save();  //将更新数据写到表中,返回受影响数量
        //UPDATE `staff` SET `name` = '龙姑娘' WHERE `staff_id` = 2

        //强烈推荐使用静态方法:update(数据,条件,字段),返回模型对象
        //改写上面案例,将龙姑娘更改回小龙女
        StaffModel::update(
            ['name'=>'小龙女'],
            ['staff_id'=>2]
        );

        //下面我们进行一个更加复杂的更新操作
        //将年龄大于50的员工的工资加500
        StaffModel::update(
            ['salary'=> \think\Db::raw('salary+500')],  //数据使用原始值调用
            function($query){   //更新条件使用闭包
                $query->where('age','>',50);
            }
        );
        //UPDATE `staff` SET `salary` = salary+500 WHERE ( `age` > 50 )

        //也可以使用查询构造器来更新数据
        StaffModel::where('age','>',50)
            ->data(['salary'=> \think\Db::raw('salary+500')])
            ->update();

        //在开发过程中,具体使用哪种方式随你,但我推荐使用静态update()方法
    }

    //模型创建: 添加数据
    public function create()
    {
        //刚才我们用save()方法添加过一条记录
        //下面我们用静态create(数据, 字段)方法来完成同样的功能
        //创建要添加的数据
        $data = [
            'name'=>'孙悟空',
            'sex' => 0,
            'age' => 100,
            'salary' => 8888
        ];

        //设置允许添加的字段名,不在列表中的字段,有值也不会添加到表中,建议表中给该字段创建默认值
        $field = ['name','sex','age','salary'];
        $field = ['name','age',];//只取两个字段，剩下的字段取默认值

        StaffModel::create($data, $field);
        //INSERT INTO `staff` (`name` , `sex` , `age` , `salary`) VALUES ('孙悟空' , 0 , 100 , 8888)
        //重复添加就会报错

        //也可以使用查询构造器添加数据,也非常简单,请课后自行练习
        StaffModel::data($data)->insert();
    }

    //模型删除: 删除记录
    public function delete()
    {
        //删除采用静态方法destory(主键/闭包)
        StaffModel::destroy(43);
        //SHOW COLUMNS FROM `staff` [ RunTime:0.000673s ]
        //SELECT * FROM `staff` WHERE `staff_id` = 43
        StaffModel::destroy([55,56,99]); //支持多主键
        //删除条件推荐使用闭包查询
        //删除年龄大于等于55岁,工资大于等于5500元的员工
        StaffModel::destroy(function($query){
            $query->where('age','>=',55)->where('salary','>=',5000);
        });

        //可以使用查询构造器删除数据:删除年龄小于20岁的员工,不执行了,结果大家应该可预知
        StaffModel::where('age','<',20)->delete();
    }

    //软删除:必须在模型中进行先行配置
    public function softDelete()
    {
        //设置数据库, 在adminer.php当中设置delete_time字段，长度10以上
        // StaffModel::destroy(1);
        //生成的SQL语句不是删除,而是更新:
        //UPDATE `staff` SET `delete_time` = 1527148290 WHERE `staff_id` = 1
        //软删除通过更新来模拟删除，给删除的记录通过更新的方式来添加一个时间戳标志

        //软删除记录不会出现在查询结果中
        $res = StaffModel::where('staff_id < 5')->select();

        //如果想让查询结果包括已经软删除的记录
        $res = StaffModel::withTrashed()->where('staff_id<5')->select();

        //如果只想查询已经被软删除的数据(回收站)
        $res = StaffModel::onlyTrashed()->select();

        dump($res);

    }

}
