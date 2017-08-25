<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/22
 * Time: 下午6:11
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test\Goods;

class TestController extends Controller
{
    public function justTest()
    {
        return Goods::where(['id'=>1])->get()->toArray();
    }
}