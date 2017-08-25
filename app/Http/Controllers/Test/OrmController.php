<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/10
 * Time: 下午5:05
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test\Country;
use App\Models\Test\Roles;
use App\Models\Test\Users;
use Illuminate\Support\Facades\DB;

class OrmController extends Controller
{
    /**
     * 一对一(根据用户获取用户的扩展信息)
     * 分两张表测试,则与一对多无恙
     */
    public function oneToOne()
    {
        DB::enableQueryLog();
        //
        //select * from `users` where `users`.`id` = 2 limit 1
        //select * from `users_extinfo` where `users_extinfo`.`user_id` = 2 and `users_extinfo`.`user_id` is not null limit 1
        //使用动态属性的话貌似过滤条件就不能加了
        $user_ext_info = Users::find(2)->usersExtInfo; //->where(['age'=>'102']);
        //$user_ext_info = Users::get()->usersExtInfo;//不可用
        var_dump($user_ext_info->toArray());

        //whereHas关联表至少要有一条数据
        /*select * from `users` where (
          select count(*) from `users_extinfo` where `users_extinfo`.`user_id` = `users`.`id` and (`id` = 2)
        ) >= 1*/
        $user_ext_info = Users::whereHas('usersExtInfo', function ($query) {
            $query->where(['id' => '2']);
        })->get();
        var_dump($user_ext_info->toArray());

        //渴求式加载
        //select * from `users`
        //'select * from `users_extinfo` where `users_extinfo`.`user_id` in (?, ?, ?, ?, ?, ?, ?)
        $user_ext_info = Users::with('usersExtInfo')->get();
        var_dump($user_ext_info);

        var_dump(DB::getQueryLog());
    }

    /**
     * 一对多
     */
    public function oneToMany()
    {
        DB::enableQueryLog();

        //关联关系方法 VS 动态属性
        //如果你不需要添加额外的条件约束到Eloquent关联查询，你可以简单通过动态属性来访问关联对象

        //select * from `users` where `users`.`id` = 3 limit 1
        //select * from `orders` where `orders`.`user_id` = 3 and `orders`.`user_id` is not null
        //如果不使用动态属性, 而是使用方法的话, 此处需要再次使用get()方法来获取数据
        $user_orders = Users::find(3)->orders()->get();
        var_dump($user_orders->toArray());

        //whereHas关联表至少要有一条数据
        /*select * from `users` where (
            select count(*) from `orders` where `orders`.`user_id` = `users`.`id` and (`id` = 2)
          ) >= 1 */
        $user_orders = Users::whereHas('orders', function ($query) {
            $query->where(['id' => '2']);
        })->get();
        var_dump($user_orders->toArray());

        ################
        ####渴求式加载####
        ################
        //select * from `users` where id = '2'
        //select * from `orders` where `orders`.`user_id` in (?, ?, ?, ?, ?, ?, ?)
        $user_orders = Users::with('orders')->where(['id' => 2])->get();
        var_dump($user_orders);

        ################
        ##懒惰渴求式加载##
        ################
        //with渴求式加载(父模型和子模型一起联查结果的情况下)
        //load懒惰渴求式加载(已经获取了父模型的情况下)
        //select * from `users`
        //select * from `orders` where `orders`.`user_id` in (?, ?, ?, ?, ?, ?, ?)
        $users = Users::all();
        $user_orders = $users->load('orders');
        var_dump($user_orders);

        var_dump(DB::getQueryLog());
    }

    /**
     * 多对多
     */
    public function manyToMany()
    {
        DB::enableQueryLog();
        //select * from `users` where (`id` = 4)
        /*
        select
            `roles`.*,
            `user_role`.`user_id` as `pivot_user_id`,
            `user_role`.`role_id` as `pivot_role_id`
        from
            `roles` inner join `user_role` on `roles`.`id` = `user_role`.`role_id`
        where
            `user_role`.`user_id` in (?)
        */
        $users = Users::where(['id' => '4'])->get();
        $user_role = $users->load('roles');
        echo '<pre>';
        var_dump($user_role->toArray());

        $users = Roles::where(['id' => '1'])->get();
        $role_user = $users->load('users');
        echo '<pre>';
        var_dump($role_user->toArray());

        var_dump(DB::getQueryLog());
    }

    /**
     * 远层一对多
     */
    public function throughOneToMany()
    {
        DB::enableQueryLog();
        //select * from `country` where (`id` = 1)
        //select `users_extinfo`.*, `users`.`country_id` from `users_extinfo` inner join `users` on `users`.`id` = `users_extinfo`.`user_id` where `users`.`country_id` in (?)
        $country = Country::where(['id' => '2'])->get();
        $country_userextinfo = $country->load('usersExtInfo');
        echo "<pre/>";
        var_dump($country_userextinfo->toArray());

        var_dump(DB::getQueryLog());
    }

    /**
     * 多态关联
     */
    public function morph()
    {

    }

    /**
     * 多对多的多态关联
     */
    public function morphedByMany()
    {

    }
}