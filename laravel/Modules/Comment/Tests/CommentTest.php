<?php

namespace Modules\Comment\Tests;

use App\Models\Administrator;
use App\Models\Model;
use App\Models\User;
use Modules\Comment\Entities\Comment;
use Tests\TestCase;
use Modules\Comment\CommentTargetTrait;

class Product extends Model
{
    use CommentTargetTrait;
}

class CommentTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testExample()
    {
        $admin = Administrator::first();
        $user = User::first();
        if (!$user) {
            $user = User::create(['nickname' => 'ron', 'avatar' => 'http://qzapp.qlogo.cn/qzapp/1105941300/25FB422853B3C00755E78F0D1DC19059/100', 'phone' => '13533732262', 'password' => md5('123456')]);
        }

        $product = Product::first();

        $comment = Comment::find(14);

        $replyComment = $product->comment([
           'content' => '这是管理员回复',
        ], $admin, $comment);

        $this->assertTrue(isset($replyComment));
    }
}
