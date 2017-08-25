<?php
/**
 * Created by PhpStorm.
 * User: renyimin
 * Date: 2017/8/22
 * Time: 下午5:07
 */

class LoginTest extends TestCase
{

    /**
     * 登录数据供给器
     */
    /*public function loginDataProvider()
    {
        return [
            ['long shorter' => ['re', '564613464@qq.com']],
            ['email format error' => ['renyimin', '564613464']],
            ['name longer' => ['renyiminrenyimin', '564613464@qq.com']]
        ];
    }*/

    /**
     * model校验
     */
    public function testModel()
    {
        $testController = new \App\Http\Controllers\Test\TestController();
        $this->assertEquals([['id' => 1, 'goods_name' => 'iphone 6 plus', 'num' => -2]], $testController->justTest());
    }
}