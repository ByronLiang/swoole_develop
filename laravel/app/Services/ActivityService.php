<?php

namespace App\Services;

use App\Models\ChatRecord;

class ActivityService extends MessageService
{
    public $fd;
    public $key;
    public $server;
    public $request;
    private $user;

    public function __construct($request = null, $fd = null, $key = null)
    {
        $this->server = app('swoole');
        $this->request = $request;
        $this->fd = $fd;
        $this->key = $key;
    }

    public function onOpenHandle()
    {
        $this->user = $this->getUserHandle($this->request->get['token']);
        if (!$this->user) {
            $data = [
                'type' => 'token expire',
            ];
            $this->server->push($this->fd, json_encode($data));
        } else {
            $data = [
                'id' => $this->user->id,
                'nickname' => $this->user->nickname,
                'avatar' => $this->user->avatar,
                'type' => 'member_online',
            ];
            $current_members = $this->getCurrentMembers($this->key);
            $this->publishDataHandle(
                $this->server,
                json_encode($data),
                $current_members
            );
            $this->storeBaseInfoHandle(
                $this->fd,
                $this->key,
                $this->user->id
            );
            if ($current_members) {
                $users = $this->getMemberUserData($current_members);
                $data = [
                    'users' => $users,
                    'type' => 'current_members',
                ];
                $this->server->push($this->fd, json_encode($data));
            }
        }
    }

    public function onMessageHandle()
    {
        $record = ChatRecord::create([
            'user_id' => $this->request['from'],
            'room' => $this->key,
            'content' => [
                'type' => $this->request['content_type'],
                'data' => $this->request['content'],
            ],
        ]);
        $current_members = $this->getCurrentMembers($this->key);
        $data = [
            'from' => $this->request['from'],
            'content' => [
                'type' => $this->request['content_type'],
                'data' => $this->request['content'],
            ],
            'type' => 'group_message',
        ];
        $this->publishDataExceptDefineFd(
            $this->server,
            json_encode($data),
            $current_members,
            $this->fd
        );
    }

    public function onCloseHandle()
    {
        list($leave_member, $res, $current_members) = $this->removeBaseInfoHandle(
            $this->fd, $this->key);
        if ($res && count($current_members) > 0) {
            $data = [
                'user_id' => current($leave_member),
                'type' => 'leave_member',
            ];
            // 通知组内成员有人离开房间
            $this->publishDataHandle(
                $this->server,
                json_encode($data),
                $current_members
            );
        }
    }
}
