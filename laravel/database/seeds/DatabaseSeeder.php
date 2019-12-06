<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // $this->call(AdminSeeder::class);

        if ('local' == config('app.env')) {
            $this->call(TestSeeder::class);
        }
    }
}
