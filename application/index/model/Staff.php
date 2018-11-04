<?php

namespace app\index\model;

/**
 * 实现软删除功能的步骤
 * 1.在数据表中新增delete_time字段(字段名可自定义)
 * 2.在模型中导入trait类:SoftDelete
 * 3.设置模型属性 protected $deleteTime = '删除时间字段名';
 * 4.设置软删除字段默认值[可选]
 *
 * 1.在表中添加一个字段: 删除时间(删除标志): delete_time
 * 2.在模型类添加一个属性: $deleteTime = 'delete_time'
 * 3.在模型中导入软删除的trait类库: SoftDelete
 * 4.最新版支持设置软删除的默认字段值
 *
 */

use think\Model;
//使用软删除功能,必须先导入model/concern/SoftDelete.php
use think\model\concern\SoftDelete;    //实际上一个trait方法集

class Staff extends Model
{
    use SoftDelete;//通过use关键字引用到当前类中
    //use相当于把所有SoftDelete.php代码复制到当前类中

    //设置数据表名
    protected $table = 'staff';

    //设置主键 由于默认为id所以需要设置
    protected $pk = 'staff_id';

    //设置删除时间字段,配合软删除功能
    protected $deleteTime = 'delete_time';

    //设置软删除字段的默认值
    protected $defaultSoftDelete = 0;

    protected function getSexAttr($value)
    {	$sex = [0=>'男', 1=>'女'];
        return $sex[$value];
    }

    protected function getSalaryAttr($value, $data)
    {
        return $data['name'].'的工资是:'.($value+200);  //生成用户订单是常用
    }

    protected function getStaffInfoAttr($value, $data)
    {
        //staff_info 虚拟字段 $value 是占位符
        //其实$value此时仅是一个占位符,可任意命名,无任何意义
        return '我是'.$data['name'].'今年都.'.$data['age'].'岁了,工资才'.$data['salary'].',好可怜呀~~';
    }

    //changer
    protected function setEntryTimeAttr($value)
    {
        return strtotime($value);
        //strtotime字符串转时间戳
    }

    protected function setSalaryAttr($value, $data)
    {
        //salary字段存入表中时,自动加上员工的年龄,纯粹演示,无实际意义
        return $value+$data['age'];
    }

    //开启当前模型的自动时间戳功能
    protected $autoWriteTimestamp = true;
    //设置支持自动时间戳功能的字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    //在表中添加create_time&update_time两个字段


    //类型转换
    //思考:为什么name字段不需要转换
    //因为从表中取出的数据默认都是字符型,name本身就是字符型,所以不用转换
    protected $type = [
        'staff_id'    =>  'integer',
        'sex'     =>  'integer',
        'age'  =>  'interger',
        'salary'      =>  'interger',
    ];

    //自动完成: 针对写操作, 新增, 更新
    //相当于给字段设置默认值
    protected $insert = ['sex' => 0,'salary'=> 1000]; //新增时
    protected $update = ['sex'=> 0];




}
