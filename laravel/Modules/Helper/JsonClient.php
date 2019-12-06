<?php

namespace Ganguo\JSend;

class JsonClient
{
    private $client;
    private $config;
    private $is_array = false;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'allow_redirects' => false,
            'http_errors' => true,
            'base_uri' => '',
        ], $config);
        $this->client = new \GuzzleHttp\Client($config);
        $this->config = $config;
    }

    public function switchArray()
    {
        $this->is_array = !$this->is_array;

        return $this;
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     *
     * @return mixed|string|array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $url, $options)
    {
        foreach (['form_params', 'json', 'query'] as $k => $v) {
            if (!empty($this->config[$v]) && !empty($options[$v])) {
                $options[$v] = array_merge($this->config[$v], $options[$v]);
            }
        }

        $res = $this->client
            ->request($method, $url, $options)
            ->getBody()
            ->getContents();
        $res = json_decode($res, $this->is_array);

        return $res;
    }

    /**
     * @param $url
     * @param array $options
     *
     * @return array|mixed|string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     *
     * @return array|mixed|string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     *
     * @return array|mixed|string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($url, $options = [])
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     *
     * @return array|mixed|string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($url, $options = [])
    {
        return $this->request('DELETE', $url, $options);
    }
}
