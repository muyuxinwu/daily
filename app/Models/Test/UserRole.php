<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

/**
 * 目前不用自定义中间表的模型, 5.4是可以自定义IDE
 * Class UserRole
 * @package App\Models\Test
 */
class UserRole extends Model
{
    /*INSERT INTO user_role (`user_id`, `role_id`)
        VALUES
        ( 1, 1),
        ( 2, 1 ),
        ( 3, 1 ),
        ( 4, 2 ),
        ( 4, 3 ),
        ( 5, 4 ),
        ( 6, 5 );*/
    //表名
    protected $table = 'roles';
    //设置时间戳格式
    protected $dateFormat = 'U';
}
