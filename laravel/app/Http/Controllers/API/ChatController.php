<?php

namespace App\Http\Controllers\API;

use App\Models\Author;
use App\Models\ChatMessage;
use App\Events\MessagePosted;

class ChatController extends Controller
{
    /**
     * @OA\Get(path="/chats",tags={"聊天室"},summary="聊天信息",description="",
     *     @OA\Parameter(name="id",in="query",description="作者ID",@OA\Schema(type="string")),
     *     @OA\Response(response=200,description="successful operation"),
     *     security={{"bearerAuth": {}}},
     * ),
     */
    public function index()
    {
        $model = Author::with(['messages' => function ($q) {
            // 显示最近五条消息
            $q->with('user')->orderBy('created_at')->limit(5);
        }])
        ->findorFail(request('id'));

        return \Response::success($model);
    }

    public function store()
    {
        $user = auth()->user();
        $message = ChatMessage::create([
            'author_id' => request('author'),
            'user_id' => $user->id,
            'message' => request('message'),
        ]);
        $message['user'] = $user;
        // Announce that a new message has been posted
        // 传入当前的作者ID（信道ID）
        broadcast(new MessagePosted($message, $user, request('author')))
            ->toOthers();

        return \Response::success($user);
    }
}
