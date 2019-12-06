<?php

namespace Modules\Helper\JSend;

class JSendResponse
{
    const ERROR = 'error';
    const SUCCESS = 'success';

    protected $data;
    protected $status;
    protected $errorCode;
    protected $errorMessage;

    /**
     * JSendResponse constructor.
     *
     * @param $status
     * @param array|null $data
     * @param null       $errorMessage
     * @param null       $errorCode
     */
    public function __construct($status, array $data = null, $errorMessage = null, $errorCode = null)
    {
        $this->data = $data;
        $this->status = $status;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param array|null $data
     *
     * @return static
     */
    public static function success(array $data = null)
    {
        return new static(static::SUCCESS, $data);
    }

    /**
     * @param $errorMessage
     * @param null       $errorCode
     * @param array|null $data
     *
     * @return static
     */
    public static function error($errorMessage, $errorCode = null, array $data = null)
    {
        return new static(static::ERROR, $data, $errorMessage, $errorCode);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = ['status' => $this->status];
        if (self::SUCCESS === $this->status) {
            if (is_array($this->data)) {
                $arr['data'] = $this->data;
            } else {
                $arr['data'] = empty($this->data) ? null : $this->data;
            }
        } elseif (self::ERROR === $this->status) {
            $arr['message'] = (string) $this->errorMessage;
            if (!empty($this->errorCode)) {
                $arr['code'] = $this->errorCode;
            }
            if (!empty($this->data)) {
                $arr['data'] = $this->data;
            }
        }

        return $arr;
    }
}
