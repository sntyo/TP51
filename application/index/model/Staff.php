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

}
