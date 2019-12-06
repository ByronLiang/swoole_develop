<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('tag_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('sort_order')->default(0)->comment('排序顺序');
            $table->boolean('fixed')->default(0)->comment('是否固定的，不可修改');
            $table->string('type')->nullable()->comment('类型');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('sort_order')->default(0);
            $table->json('extend')->nullable()->comment('扩展');
            $table->timestamps();

            $table->unsignedInteger('tag_group_id')->nullable();
            $table->foreign('tag_group_id')->references('id')->on('tag_groups')->onDelete('cascade');
        });

        Schema::create('tag_gables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->text('extend')->nullable();

            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->morphs('gable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tag_gables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_groups');
    }
}
