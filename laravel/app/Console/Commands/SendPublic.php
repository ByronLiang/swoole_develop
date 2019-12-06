<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\WechatScanLogin;

class SendPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:public_channel {content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test broadcast public channel';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        broadcast(new WechatScanLogin($this->argument('content')))->toOthers();
    }
}
