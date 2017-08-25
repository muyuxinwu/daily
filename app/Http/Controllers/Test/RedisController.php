<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/25
 * Time: 下午9:40
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    /**
     * 这个命令其实比较特殊
     * 注意的点很多, 具体需要仔细看手册
     */
    public function scan()
    {
        var_dump(Redis::scan(0));
    }
}