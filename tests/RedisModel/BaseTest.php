<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\RedisModel;

use PHPUnit\Framework\TestCase;
use limx\Tests\App\RedisModel\User;

class BaseTest extends TestCase
{
    public function testBase()
    {
        $this->assertTrue(true);
    }

    public function testReplaceCase()
    {
        $user = new User();
        $data = [
            'id' => 1,
            'username' => 'limx',
            'name' => '李铭昕'
        ];
        $res = $user->replace(1, $data);
        $this->assertTrue($res);
    }
}