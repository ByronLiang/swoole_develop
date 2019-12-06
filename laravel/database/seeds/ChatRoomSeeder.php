<?php

use Illuminate\Database\Seeder;
use App\Models\AuthorChatRoom;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AuthorChatRoom::insert([
            ['author_id' => 1, 'room_no' => 1, 'listener' => 'free'],
            ['author_id' => 2, 'room_no' => 2, 'listener' => 'free'],
            ['author_id' => 3, 'room_no' => 3, 'listener' => 'free'],
            ['author_id' => 4, 'room_no' => 4, 'listener' => 'free'],
            ['author_id' => 5, 'room_no' => 5, 'listener' => 'free'],
            ['author_id' => 6, 'room_no' => 6, 'listener' => 'free'],
        ]);
    }
}
