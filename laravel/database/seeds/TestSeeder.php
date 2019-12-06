<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\User::class, 16)->create();
        factory(\App\Models\Banner::class, 5)->create();
        factory(\App\Models\Author::class, 6)->create();
        $this->call(ChatRoomSeeder::class);
    }
}
