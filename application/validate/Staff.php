<?php
namespace app\validate;
//创建系统验证类

use think\Validate;
//继承框架验证类

class Staff extends Validate
{
    //创建验证规则
    //以属性的方式进行配置,属性不能更改
    //第一个验证规则require必填
    //in: 验证某个字段的值是否在某个范围
    //between: 验证某个字段的值是否在某个区间
    //length:num1,num2: 验证某个字段的值的长度是否在某个范围
    //egt 或者 >=
    //gt 或者 >
    //eq 或者 = 或者 same
    protected $rule = [
        'name'=>'require|min:5|max:15',
        'sex' => 'in:0,1',
        'age' => 'require|between:18,60',
        'salary' => 'require|gt: 1500'
    ];

    //错误信息可以自定义:
    protected $message = [
        'name.require' => '员工姓名不能为空',
        'name.min' => '姓名不能少于5个字符',
        'name.max' => '姓名不能大于15个字符',
        'sex.in' => '性别只能选择男或女',
        'age.require' => '年龄必须输入',
        'age.between' => '年龄必须在18到60周岁之间',
        'salary.require' => '工资必须输入',
        'salary.gt' => '工资必须大于1500元'
    ];
}

