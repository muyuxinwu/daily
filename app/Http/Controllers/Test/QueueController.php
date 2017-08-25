<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/21
 * Time: 下午5:31
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class QueueController extends Controller
{

    /**
     * 通过将控制器方法封装成命令来测试redis列表模拟消息队列
     * @return mixed
     */
    public function redisListBrpop()
    {
        $res = Redis::brpop(['j1', 'j2'], 0);
        return $res;
    }

    /**
     * redis的订阅
     */
    public function redisSubScribe()
    {
        //一旦运行就会阻塞起来
        Redis::subscribe(['cctv1'], function($message) {
            var_dump($message);
        });
    }
}