<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaAblesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('meta_ables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('null');
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            $table->morphs('able');
            $table->index([
                'able_id',
                'able_type',
                'key',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('meta_ables');
    }
}
