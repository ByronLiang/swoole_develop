<?php

namespace App\Http\Controllers\API;

use App\Models\ChatRecord;

class ChatRecordsController extends Controller
{
    public function index()
    {
        $records = ChatRecord::with('user')
            ->whereRoom(request('room'))
            ->orderByDesc('id')
            ->select('content', 'user_id', 'id')
            ->paginate();

        return \Response::success(new \App\Http\Resources\GanguoCollection($records));
    }
}
