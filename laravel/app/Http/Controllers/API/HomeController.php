<?php

namespace App\Http\Controllers\API;

use App\Models\Banner;
use App\Models\Author;
use App\Events\TaskEvent;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use App\Tasks\BasicTask;

class HomeController extends Controller
{
    public function index()
    {
        // $res = Event::fire(new TaskEvent('task data'));
        // \Log::info($res);
        // $ret = Task::deliver(new BasicTask('kkk'));
        // \Log::info($ret);
        $banner = Banner::where('status', 1)->get();
        $author = Author::with('room')->limit(6)->get();

        return \Response::success(compact('banner', 'author', 'index'));
    }
}
