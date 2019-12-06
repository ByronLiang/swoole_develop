<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('administrators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account', 64)->index();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar')->default('');
            $table->string('nickname')->default('');
            $table->string('account', 64)->default('')->index();
            $table->string('password')->default('');
            $table->string('api_token', 64)->default('')->index();
            $table->rememberToken();
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('avatar');
            $table->string('introduction');
            $table->integer('number');
            $table->string('title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('message');
            $table->integer('author_id');
            $table->timestamps();
        });

        Schema::create('author_chat_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->string('room_no')->comment('房间号');
            $table->string('listener')->comment('收听用户限制');
            $table->string('pay_rule')->nullable()->comment('付费规则');
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable();
            $table->string('resource')->nullable()->comment('跳转参数');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('type')->default(1);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('administrators');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('author_chatrooms');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('notifications');
    }
}
