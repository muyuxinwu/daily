<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    /*INSERT INTO roles (`role_name`)
        VALUES
        ( 'r1' ),
        ( 'r2' ),
        ( 'r3' ),
        ( 'r4' ),
        ( 'r5' ),
        ( 'r6' ),
        ( 'r7' );*/
    //表名
    protected $table = 'roles';
    //设置时间戳格式
    protected $dateFormat = 'U';

    /**
     * roles模型(roles表)和users模型(users表)为 '多对多关联', 多对多其实就没有主表和副表之分了
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        //第一个参数为关联的模型
        //第二个参数为中间表的表名,不能写模型名::class (5.4可以自定义中间表模型, 会有using()用法)
        //第三个参数为关联的模型所对应的外键(一般貌似就是本表的主键)
        //第四个参数为本表的外键
        return $this->belongsToMany(\App\Models\Test\Users::class, 'user_role', 'role_id', 'user_id');
    }
}
