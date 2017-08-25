<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/17
 * Time: 下午10:13
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ConcurrentController extends Controller
{

    /**
     * 使用mysql模拟并发超卖
     * 为了简单测试,这里不使用用repository了,直接在控制器层调用orm
     */
    public function mysqlOverSell()
    {
        $good = Goods::select('num')->find(1);
        if ($good['num']>0) {
            usleep(500000);
            $res = Goods::where(['id' => 1])->decrement('num', 1);//->update(['num' => $good['num']-1]);
        }
    }

    /**
     * 使用redis模拟并发超卖
     */
    public function redisOversell()
    {
        $good = Redis::get('num');
        if ($good > 0) {
            Redis::multi();
            //usleep(500000);
            //预先已经设置好库存为10个了
            Redis::decr('num');
            Redis::hSet("successUsers", "user_id" . mt_rand(1, 999999), microtime());
            Redis::exec();
        }
    }

    /**
     * redis: Watch+事务
     * 解决并发超卖问题
     */
    public function redisWatchConcurrent()
    {
        $good = Redis::get('num');
        //watch库存量
        Redis::watch('num');
        if ($good > 0) {
            Redis::multi();
            usleep(500000);
            //预先已经设置好库存为10个了
            Redis::decr('num');
            Redis::hSet("successUsers", "user_id" . mt_rand(1, 999999), microtime());
            Redis::exec();
        }
    }

    /**
     * 用mysql的行锁来实现
     */
    public function mysqlAtomLock()
    {
        //echo '<pre/>';
        //DB::enableQueryLog();
        $good = Goods::select('num')->find(1);
        if ($good['num']>0) {
            usleep(500000);
            $res = Goods::where(['id' => 1])
                ->where('num', '>', '0')
                ->decrement('num', 1);
        }
        //var_dump(DB::getQueryLog());
    }

    public function flashSale1()
    {
//        echo '<pre/>';
//        DB::enableQueryLog();
        //每来一次访问,库存减一
        //先查询剩余数量
//        $num = Goods::where(['id' => 1])
//            ->where('num', '>=', 1)
//            ->decrement('num', 1);

        $good = Goods::select('num')->find(1);
//        var_dump($good['num']);
        if ($good['num']>0) {
            usleep(500000);
            $res = Goods::where(['id' => 1])->decrement('num', 1);//->update(['num' => $good['num']-1]);
        }
//        var_dump($res);
//        var_dump(DB::getQueryLog());
    }
}