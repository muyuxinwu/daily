<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class UsersExtInfo extends Model
{
    /*INSERT INTO users_extinfo (`user_id`, `avatar`, `resume`)
        VALUES
        ( 1, 'a1', 'r1' ),
        ( 2, 'a2', 'r2' ),
        ( 3, 'b3', 'r3' ),
        ( 4, 'c4', 'r4' ),
        ( 5, 'd5', 'r5' ),
        ( 6, 'e6', 'r6' ),
        ( 7, 'f7', 'r7' );*/
    //表名
    protected $table = 'users_extinfo';
    //设置时间戳格式
    protected $dateFormat = 'U';

    /**
     * users模型(users表)和usersExtInfo模型(users_extinfo表)为 '一对一关联' , users为主表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        //参数一: 关联表
        //参数二: 两个表中'副表'中的外键
        //参数三: 两个表各自的主键(默认不写就都是id)
        return $this->belongsTo(\App\Models\Test\Users::class, 'user_id', 'id');
    }
}
