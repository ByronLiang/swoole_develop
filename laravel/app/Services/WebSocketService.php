<?php

namespace App\Services;

use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;

class WebSocketService implements WebSocketHandlerInterface
{
    public function __construct()
    {
    }

    public function onOpen(Server $server, Request $request)
    {
        $key = $request->get['prefix'].'_'.$request->get['prefix_id'];
        $classInstance = $this->processHandleInstance($request->get['prefix'], $request->fd, $key, $request);
        if ($classInstance) {
            $classInstance->onOpenHandle();
        }
    }

    public function onMessage(Server $server, Frame $frame)
    {
        \Log::info('Received message', [$frame->fd, $frame->data, $frame->opcode, $frame->finish]);
        if ($frame->finish) {
            $receive = json_decode($frame->data, true);
            if ('ping' == $receive['type']) {
                $data = ['time' => date('Y-m-d H:i:s'), 'type' => 'pong'];
                $server->push($frame->fd, json_encode($data));
            } else {
                $key = $receive['prefix'].'_'.$receive['prefix_id'];
                $classInstance = $this->processHandleInstance($receive['prefix'], $frame->fd, $key, $receive);
                if ($classInstance) {
                    $classInstance->onMessageHandle();
                }
            }
        }
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        $msg_handle = new MessageService();
        list($key_data, $res) = $msg_handle->removeSocketTableData($fd);
        if ($res) {
            $prefix = $msg_handle->getKeyInstance($key_data);
            $classInstance = $this->processHandleInstance($prefix, $fd, $key_data);
            if ($classInstance) {
                $classInstance->onCloseHandle();
            }
        }
    }

    private function processHandleInstance($prefix, $fd, $key, $receive = null)
    {
        $class_name = 'App\Services\\'.ucwords($prefix).'Service';
        if (class_exists($class_name)) {
            $class = new \ReflectionClass($class_name);
            $classInstance = $class->newInstance($receive, $fd, $key);

            return $classInstance;
        } else {
            return false;
        }
    }
}
