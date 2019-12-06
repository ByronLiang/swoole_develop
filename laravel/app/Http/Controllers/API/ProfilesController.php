<?php

namespace App\Http\Controllers\API;

class ProfilesController extends Controller
{
    /**
     * @OA\Get(path="/my/profile",tags={"个人中心"},summary="个人信息",description="",
     *     @OA\Response(response=200,description="successful operation"),
     *     security={{"bearerAuth": {}}},
     * ),
     */
    public function getProfile()
    {
        return \Response::success($this->my);
    }
}
