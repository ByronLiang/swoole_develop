<?php

namespace Modules\ClientAggregationUpload\Http\Controllers;

use Modules\ClientAggregationUpload\Factory as ClientAggregationUpload;
use Illuminate\Routing\Controller;

class SupplierController extends Controller
{
    /**
     * @return mixed
     */
    public function getUpload()
    {
        $data = (new ClientAggregationUpload(request('drive')))->getParameter();

        return \Response::success($data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function postUpload(\Illuminate\Http\Request $request)
    {
        $data = ClientAggregationUpload::localStore($request);

        return \Response::success($data);
    }
}
