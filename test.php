<?php

class demo1{}
class demo2{}
class demo3{}
class demo4{}

class regedit
{
    //静态公有属性来存储对象集合
    public static $objs = [];
    //将对象储存进数组 上树
    public static function set($index, $value)
    {
        self::$objs[$index] = $value;
    }
    //将对象取出来使用
    public static function get($index)
    {
        return self::$objs[$index];
    }
    //使用完销毁 节省资源
    public static function delete($index)
    {
        unset(self::$objs[$index]);
    }
}
//上树
regedit::set('demo1', new demo1());
regedit::set('demo2', new demo2());
regedit::set('demo3', new demo3());
regegit::set('demo4', new demo4());
//检测是否上树
var_dump(regedit::$objs);
var_dump(regedit::get('demo2'));
regedit::delete('demo1');
var_dump(regedit::$objs);








?>