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
    protected $id = 1;
    protected $data = [
        'id' => 1,
        'username' => 'limx',
        'name' => '李铭昕'
    ];

    public function testBase()
    {
        $this->assertTrue(true);
    }

    public function testReplaceCase()
    {
        $user = new User();
        $res = $user->replace($this->id, $this->data);
        $this->assertTrue($res);
    }

    public function testTtlCase()
    {
        $user = new User();
        $res = $user->replace($this->id, $this->data, 60);
        $this->assertTrue($res);

        $this->assertTrue($user->where('id', 1)->ttl() > 0);
    }

    public function testUpdateCase()
    {
        $user = new User();
        $data = $this->data;
        $data['username'] = 'lmx';
        $user->where('id', 1)->update($data, 60);

        $res = $user->where('id', 1)->first();
        $this->assertEquals($data, $res);
    }
}