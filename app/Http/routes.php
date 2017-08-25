<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'test', 'namespace' => 'Test'], function () {
    //一对一
    Route::get('oneToOne', ['uses' => 'OrmController@oneToOne']);
    //一对多
    Route::get('oneToMany', ['uses' => 'OrmController@OneToMany']);
    //多对多
    Route::get('manyToMany', ['uses' => 'OrmController@manyToMany']);
    //远层一对多
    Route::get('throughOneToMany', ['uses' => 'OrmController@throughOneToMany']);
});

/**
 * 压测并发
 * 抢购测试
 */
Route::group(['prefix' => 'Concurrent', 'namespace' => 'Test'], function () {
    //mysql模拟并发超卖
    Route::get('mysqloversell', ['uses' => 'ConcurrentController@mysqlOverSell']);
    //当然,redis并发也会出现超卖的问题
    Route::get('redisoversell', ['uses' => 'ConcurrentController@redisOversell']);
    //redis 事务+watch乐观锁 解决超卖
    Route::get('rediswatch', ['uses' => 'ConcurrentController@redisWatchConcurrent']);
    Route::get('mysqltomlock', ['uses' => 'ConcurrentController@mysqlAtomLock']);
});

/**
 * redis消息队列
 */
Route::group(['prefix' => 'Queue', 'namespace' => 'Test'], function () {
    //redis list的blpop模拟队列
    Route::get('redislistbrpop', ['uses' => 'QueueController@redisListBrpop']);

    //redis 的pub/sub
    Route::get('redissubscribe', ['uses' => 'QueueController@redisSubscribe']);
});

/**
 * redis操作测试
 */
Route::group(['prefix' => '', 'namespace' => 'Test'], function () {
    Route::get('redis', ['uses' => 'RedisController@scan']);
});

/**
 * 单元测试
 */
Route::group(['prefix' => 'Test', 'namespace' => 'Test'], function () {
    Route::get('test', ['uses' => 'TestController@justTest']);
});