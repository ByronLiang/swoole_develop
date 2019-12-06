<?php

namespace Modules\ClientAggregationUpload;

class Factory
{
    private $drive;

    /**
     * @var FactoryInterface
     */
    private $object_storage;

    public function __construct($drive = '')
    {
        $drive = $drive ?: config('client_aggregation_upload.default') ?: 'local';

        switch ($drive) {
            case 'oss':
                $this->object_storage = new AliyunOssService();
                break;
            case 'cos':
                $this->object_storage = new TencentCosService();
                break;
            case 'qiniu':
                $this->object_storage = new QiniuService();
                break;
            case 'aws':
                $this->object_storage = new AwsS3Service();
                break;
            case 'local':
                $this->object_storage = new LocalService();
                break;
            default:
                abort(500, '不支持此存储参数');
                break;
        }

        if (!$this->object_storage instanceof FactoryInterface) {
            abort(500, '不支持此存储模式');
        }

        $this->drive = $drive;
    }

    public function getParameter()
    {
        $drive = $this->drive;

        $form = $this->object_storage->getForm();

        $headers = $this->object_storage->getHeaders();

        $access_url = $this->object_storage->getAccessUrl();

        $upload_url = $this->object_storage->getUploadUrl();

        $file_field = $this->object_storage->getFileField();

        return compact('drive', 'form', 'headers', 'access_url', 'upload_url', 'file_field');
    }

    public static function localStore(\Illuminate\Http\Request $request)
    {
        (new LocalService())->securityCheck($request);

        $file = $request->file('file');

        if ($file && !$file->isValid()) {
            return abort(422, '上传失败');
        }
        $file->move('upload', $request->key);

        return [
            'key' => $request->key,
        ];
    }

    public static function routesHook()
    {
        \Route::get('upload', '\Modules\ClientAggregationUpload\Http\Controllers\SupplierController@getUpload');

        \Route::post('upload', '\Modules\ClientAggregationUpload\Http\Controllers\SupplierController@postUpload');
    }
}
