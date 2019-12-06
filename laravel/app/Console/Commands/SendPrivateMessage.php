<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\PrivateMessageUser;

class SendPrivateMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:private_msg {content} {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test broadcast private channel in define one person';

    public function handle()
    {
        broadcast(new PrivateMessageUser($this->argument('content'), $this->argument('user_id')));
    }
}
