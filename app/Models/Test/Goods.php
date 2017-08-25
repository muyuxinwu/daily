<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    /*INSERT INTO goods (`goods_name`, `num`)
    VALUES
    ( 'iphone 6 plus', 100 ),
    ( 'huawei', 200 );*/
    //表名
    protected $table = 'goods';
    public $timestamps = false;
}
