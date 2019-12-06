<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivingPlacesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('receiving_places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('phone');
            $table->string('address');
            $table->boolean('is_default')->default(0);
            $table->timestamps();

            $table->morphs('able');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('receiving_places');
    }
}
