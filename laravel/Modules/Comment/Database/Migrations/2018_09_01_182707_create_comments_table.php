<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content');
            $table->unsignedInteger('reply_id')->nullable()->comment('回复评论ID');
            $table->foreign('reply_id')->references('id')->on('comments')->onDelete('cascade');
            $table->string('extend')->nullable()->comment('扩展数据');
            $table->timestamps();

            $table->softDeletes();
            $table->morphs('user');
            $table->morphs('target');
        });

        Schema::create('comment_gables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('comment_id')->index();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');

            $table->morphs('gable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('comment_gables');
        Schema::dropIfExists('comments');
    }
}
