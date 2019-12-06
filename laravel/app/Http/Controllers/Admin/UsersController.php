<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RESTfulTrait;
use App\Models\User;

class UsersController extends Controller
{
    use RESTfulTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setRESTfulModel(User::query());
    }

    /**
     * @param string                         $type
     * @param \Illuminate\Support\Collection $models
     *
     * @return \App\Exports\BaseExport
     */
    public function export(string $type, \Illuminate\Support\Collection $models)
    {
        if (in_array($type, ['xls', 'xlsx'])) {
            return new \App\Exports\BaseExport($models, 'users.xls', [
                'id',
                '头像',
                '昵称',
                '手机号',
                '创建时间',
                '修改时间',
                '冻结时间',
            ]);
        }

        return abort(400, '导出参数不支持');
    }

    public function destroy($id)
    {
        return $this->delete($id);
    }
}
