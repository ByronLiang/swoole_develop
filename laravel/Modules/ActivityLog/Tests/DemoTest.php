<?php

namespace Modules\ActivityLog\Tests;

use Modules\ActivityLog\Entities\ActivityLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testExample()
    {
        activity()->log('测试， 这是一个操作日志');

        $res = ActivityLog::get();

        dd($res);

        $this->assertTrue(true);
    }
}
