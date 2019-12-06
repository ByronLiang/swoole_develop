<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Redis;

class MessageService
{
    public function action($type, ...$arg)
    {
        $method = $type.'Handle';
        if (method_exists($this, $method)) {
            return $this->{$method}($arg);
        } else {
            throw new \Exception('Error type handle');
        }
    }

    public function getUserHandle($token)
    {
        $user = User::where('api_token', $token)->first();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function getCurrentMembers($key)
    {
        // 提取房间成员的有序集合
        return Redis::zrange($key, 0, -1, ['withscores' => true]);
    }

    public function getMemberUserData($member_datas)
    {
        $user_ids = array_keys($member_datas);
        $users = User::select('id', 'nickname', 'avatar')
            ->whereIn('id', $user_ids)
            ->get();

        return $users;
    }

    public function publishDataHandle($websocket_server, $data, $member_datas)
    {
        // 提取房间成员的fd
        $lists = array_values($member_datas);
        foreach ($lists as $list) {
            app('swoole')->push($list, $data);
        }
    }

    public function publishDataExceptDefineFd($websocket_server, $data, $member_datas, $except_fd)
    {
        // 提取房间成员的fd
        $lists = array_values($member_datas);
        foreach ($lists as $list) {
            if ($list != $except_fd) {
                app('swoole')->push($list, $data);
            }
        }
    }

    /**
     * 存储基本用户id与房间信息.
     */
    public function storeBaseInfoHandle($fd, $key, $user_id)
    {
        Redis::pipeline();
        // 对多socket存储对应key关系
        Redis::zadd('socket_table', $fd, $user_id.':'.$key);
        Redis::zadd($key, $fd, $user_id);
        Redis::exec();
    }

    public function getKeyData($fd)
    {
        return Redis::ZRangeByScore('socket_table', $fd, $fd);
    }

    /**
     * 移除socket表.
     */
    public function removeSocketTableData($fd)
    {
        Redis::pipeline();
        Redis::ZRangeByScore('socket_table', $fd, $fd);
        Redis::ZREMRANGEBYSCORE('socket_table', $fd, $fd);

        return Redis::exec();
    }

    public function getKeyInstance($key_data)
    {
        list($user_id, $key) = explode(':', current($key_data));
        list($prefix, $prefix_id) = explode('_', $key);

        return $prefix;
    }

    public function removeBaseInfoHandle($fd, $data)
    {
        list($user_id, $key) = explode(':', current($data));
        Redis::pipeline();
        // 获取离开房间的user_id
        Redis::ZRangeByScore($key, $fd, $fd);
        // 离开房间, 移除用户
        Redis::ZREMRANGEBYSCORE($key, $fd, $fd);
        // 当前房间的剩余用户
        Redis::zrange($key, 0, -1, ['withscores' => true]);

        return Redis::exec();
    }

    public function testHandle($fd, $target_id, $key)
    {
        Redis::lpush($key.$target_id, $fd);

        return Redis::lrange($key.$target_id, 0, -1);
    }
}
