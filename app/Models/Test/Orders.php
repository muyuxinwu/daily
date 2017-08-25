<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /*INSERT INTO orders (`user_id`, `order_no`)
        VALUES
        ( '1', 'order1' ),
        ( '1', 'order2' ),
        ( '3', 'order3' ),
        ( '4', 'order4' ),
        ( '3', 'order5' ),
        ( '2', 'order6' ),
        ( '4', 'order7' );*/
    //表名
    protected $table = 'orders';
    //设置时间戳格式
    protected $dateFormat = 'U';

    /**
     * orders模型(orders表)和users模型(users表)为 '多对一关联' , users为主表
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
