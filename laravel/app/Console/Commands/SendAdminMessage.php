<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\PrivateMessageAdmin;

class SendAdminMessage extends Command
{
    protected $signature = 'send:admin_msg {content} {user_id}';

    protected $description = 'test broadcast private channel in define one person';

    public function handle()
    {
        broadcast(new PrivateMessageAdmin($this->argument('content'), $this->argument('user_id')));
    }
}
