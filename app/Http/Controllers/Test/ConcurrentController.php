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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ConcurrentController extends Controller
{

    /**
     * 使用mysql模拟并发超卖
     * 结果确实超卖了
     * 为了简单测试,这里不使用用repository了,直接在控制器层调用orm
     */
    public function mysqlOverSell()
    {
        //echo '<pre/>';
        //DB::enableQueryLog();
        $good = Goods::select('num')->find(1);
        if ($good['num']>0) {
            usleep(500000);
            Goods::where(['id' => 1])->decrement('num', 1);
            //接下来的操作自然也无法进行了
        }
        //var_dump(DB::getQueryLog());
        //update `goods` set `num` = `num` - 1 where (`id` = 1)
    }

    /**
     * 使用mysql模拟并发超卖
     * 相对于上一种模拟方法, 修改sql语句(合并为一条update语句), 使用mysql的排它锁
     */
    public function mysqlOverSellSql()
    {
        //TODO 有异议(此处锁的理解并不透彻)
        //echo '<pre/>';
        //DB::enableQueryLog();
        Goods::where(['id' => 1])
            ->where('num', '>', '0')
            ->decrement('num', 1);
        //var_dump(DB::getQueryLog());
        //update `goods` set `num` = `num` - 1 where (`id` = 1) and `num` > 0
    }

    /**
     * 使用redis模拟并发超卖 (这里和事务其实没什么关系, 主要是可能会有多个redis操作)
     */
    public function redisOversell()
    {
        $good = Redis::get('num');
        if ($good > 0) {
            Redis::multi();
            usleep(500000);
            //预先已经设置好库存为10个了
            Redis::decr('num');
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
            //之后的业务逻辑,如果涉及到其他redis键的操作,也是需要提前对它加watch
            //.....
            $res = Redis::exec();
            Log::info($res);
            //注意:接下来的业务都需要在事务成功之后进行哦(否则事务失败也进行了,那就没有意义了)
            if ($res) {
                //接下来的是模拟后续的mysql操作 (其实还有疑问就是如果后续操作失败的话... 如何保证回滚?)
                usleep(500000);
                Goods::where(['id' => 1])->decrement('num', 1);
            }
        }
    }

    /**
     * redis: 原子操作解决问题
     * 解决并发超卖问题
     */
    public function redisAtomicConcurrent()
    {
        //注意不要将num的值取出来之后, 再if判断库存, 否则会出现幻读, 导致超卖
        //不然就和'redisOversell'方法中的类似了(只不过'redisOversell'方法中多了对事务的使用)
        $num = Redis::decr('num');
        if ($num > -1) {
            usleep(500000);
            Log::info('yes');
            //接下来的是模拟后续的mysql操作 (其实还有疑问就是如果后续操作失败的话... 如何保证回滚?)
            Goods::where(['id' => 1])->decrement('num', 1);
        } else {
            //注意: 如果出现并发购买减库存的时候,需要把数量加回去
            Redis::incr('num');
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