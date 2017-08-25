<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
        /*INSERT INTO users (`username`, `age`)
        VALUES
        ( 'n1', '101' ),
        ( 'n2', '102' ),
        ( 'n3', '103' ),
        ( 'n4', '104' ),
        ( 'n5', '105' ),
        ( 'n6', '106' ),
        ( 'n7', '107' );*/
    //表名
    protected $table = 'users';
    //设置时间戳格式
    protected $dateFormat = 'U';

    /**
     * users模型(users表)和usersExtInfo模型(users_extinfo表)为 '一对一关联' , users为主表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usersExtInfo()
    {
        //参数一: 关联表
        //参数二: 两个表中'副表'中的外键
        //参数三: 两个表各自的主键(默认不写就都是id)
        return $this->hasOne(\App\Models\Test\UsersExtInfo::class, 'user_id', 'id');
    }

    /**
     * users模型(users表)和orders模型(orders表)为 '一对多关联' , users为主表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orders()
    {
        //参数一: 关联表
        //参数二: 两个表中'副表'中的外键
        //参数三: 两个表各自的主键(默认不写就都是id)
        return $this->hasMany(\App\Models\Test\Orders::class, 'user_id', 'id');
    }

    /**
     * users模型(users表)和roles模型(roles表)为 '多对多关联', 多对多其实就没有主表和副表之分了
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        //第一个参数为关联的模型
        //第二个参数为中间表的表名,不能写模型名::class (5.4可以自定义中间表模型, 会有using()用法)
        //第三个参数为关联的模型所对应的外键(一般貌似就是本表的主键)
        //第四个参数为本表的外键
        return $this->belongsToMany(\App\Models\Test\Roles::class, 'user_role', 'user_id', 'role_id');
    }
}
