<?php

namespace Modules\Wechat\Entities;

class TemplateMessage
{
    private $wechat;
    private $app;

    public function __construct()
    {
        if (!$this->wechat) {
            $this->wechat = new Wechat();
        }
        $this->app = $this->wechat->getApplication();
    }

    public function getTemplate()
    {
        return $this->app->template_message->getPrivateTemplates();
    }

    public function send($open_id, $content, $template_key, $url = null)
    {
        $data = $this->createDataPack($open_id, $content, $template_key, $url);
        $this->app->template_message->send($data);
    }

    public function createDataPack($open_id, $content, $template_key, $url)
    {
        $basic = [
            'touser' => $open_id,
            'template_id' => Config('wechat_template.'.$template_key),
            'data' => $content,
        ];
        if ($url) {
            $basic = array_merge($basic, ['url' => $url]);
        }

        return $basic;
    }
}
