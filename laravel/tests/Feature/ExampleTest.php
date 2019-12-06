<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest()
    {
        $msg = (new \App\Services\MessageService());
        $res = $msg->testHandle(5, 12, 'activity_');
        // $response = $this->get('/');
        dd($res);
        // $response->assertStatus(200);
    }
}
