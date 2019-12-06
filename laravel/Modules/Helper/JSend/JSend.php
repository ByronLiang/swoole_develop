<?php

namespace Modules\Helper\JSend;

class JSend
{
    /**
     * @param $data
     *
     * @return array|string|null
     */
    private function format($data)
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }

        if (is_object($data)) {
            if (method_exists($data, 'response')) {
                $data = $data->response();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } elseif (method_exists($data, 'getAttributes')) {
                $data = $data->getAttributes();
            } elseif (method_exists($data, '__toString')) {
                $data = $data->__toString();
            } elseif ($data instanceof \stdClass && $data === new \stdClass()) {
                $data = null;
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::format($value);
            }
        }

        return $data;
    }

    /**
     * @param null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null)
    {
        $success = JSendResponse::success(static::format($data));

        return response()->json($success->toArray(), 200);
    }

    /**
     * @param null $errorMessage
     * @param null $errorCode
     * @param null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($errorMessage = null, $errorCode = null, $data = null)
    {
        $error = JSendResponse::error($errorMessage, $errorCode, static::format($data));

        $status = 202;
        if ($errorCode >= 400 && $errorCode < 600) {
            $status = $errorCode;
        }

        return response()->json($error->toArray(), $status);
    }
}
