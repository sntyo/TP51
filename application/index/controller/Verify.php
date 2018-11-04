<?php
namespace app\index\controller;

use think\Controller;
use app\validate\Staff; //导入验证器类
use think\Validate;//导入验证类
//验证的两种方式: 验证器模式 & 独立验证

class Verify extends Controller
{
    //验证器: 直接实例化验证器完成验证
    //通常场景是外部数据传参于此
    public function demo1()
    {
        //准备要验证的数据
        $data = [
            // 'name'=>'zhu',
            'name'=>'zhulaoshi',
            'sex' => 3,
            'age' => 15,
            'salary' => 1200
        ];

        // $data = [
        // 	'name'=>'zhulaoshi',
        // 	'sex' => 0,
        // 	'age' => 26,
        // 	'salary' => 7200
        // ];

        //实例化验证类（验证器）,获取验证对象
        //check() 验证方法 getError()报错方法
        //为具体的验证场景或者数据表定义好验证器类，直接调用验证类的check方法即可完成验证
        $validate = new Staff();
        if (!$validate->check($data)) {
            dump($validate->getError());
        } else {
            return '验证通过';
        }
    }

    //验证器的简化
    //验证器: 使用控制器内容的验证对象来完成验证: $this->validate($data, $rule)
    public function demo2()
    {
        //准备要验证的数据
        $data = [
            // 'name'=>'zhu',
            'name'=>'zhulaoshi',
            'sex' => 0,
            'age' => 2225,
            'salary' => 1600
        ];

        //准备一个验证规则
        //验证规则就是验证器
        $rule = 'app\validate\Staff';

        //自定义验证方式传参validate(
        $data = ['age'=>8];
    )
        $rule = [
            'age' => 'between:10,50',
        ];
        $message = [
            'age.between' => '年龄必须在10到50之间'
        ];
        // $res = $this->validate($data,$rule);
        // $data 验证数据 $rule 验证规则 $message 验证信息
        $res = $this->validate($data,$rule,$message);
        if (true !== $res) {	  //验证成功返回true,否则返回错误信息
            return $res;
        }
        return '验证成功';
    }

    //独立验证: 直接实例化think\Validate.php进行验证
    public function demo3()
    {
        //主要是通过Validate::make()和check()进行验证
        //make($rule,$mess):创建验证规则与错误信息并返回实例对象
        //check($data)完成数据验证

        //1.创建验证规则
        $rule = [
            'age' => 'require|between:20,60'
        ];

        //2.创建错误信息
        $mess = [
            'age.require' => '年龄必须填写',
            'age.between' => '年龄必须在20到60之间'
        ];
        //报错和验证是一一对应的

        //3.创建验证数据
        $data = ['age' => 13];

        //初始化验证器类,并返回验证器实例
        //make方法直接传入验证规则（数组）
        //check方法传入需要验证的数据（数组）
        $validate = Validate::make($rule, $mess);
        // $validate = \think\Validate::make($rule, $mess);//或者在脚本顶部通过use引入

        $res = $validate->check($data);

        return $res ? '验证通过' : $validate->getError();
    }


}
