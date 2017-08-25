<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /*INSERT INTO country (`country`)
        VALUES
        ( 'CHINA' ),
        ( 'usa' ),
        ( 'England' ),
        ( 'Japan' ),
        ( 'Korea' ),
        ( 'India' );*/
    //表名
    protected $table = 'country';
    //设置时间戳格式
    protected $dateFormat = 'U';

    /**
     * 远层一对多
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function usersExtInfo()
    {
        // 第一个传递到 hasManyThrough 方法的参数是最终我们希望访问的模型的名称
        // 第二个参数是 中间模型名称
        // 第三个参数是 中间模型的外键 (user表是中间表, 它的外键是country_id)
        // 第四个参数是 最终模型的外键 (users_extInfo表对于users表的外键是user_id)
        return $this->hasManyThrough(\App\Models\Test\UsersExtInfo::class, \App\Models\Test\Users::class,'country_id', 'user_id');
    }
}
