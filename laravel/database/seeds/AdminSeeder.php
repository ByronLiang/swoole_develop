<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Administrator::create([
            'account' => 'admin',
            'password' => '123456',
        ]);
    }
}
