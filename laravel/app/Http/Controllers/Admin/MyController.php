<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProfileRequest;

class MyController extends Controller
{
    public function getProfile()
    {
        return \Response::success(auth()->user());
    }

    public function putProfile(ProfileRequest $request)
    {
        $my = auth()->user();
        $my->update($request->extractInputFromRules());

        return \Response::success($my);
    }

    public function getLogout()
    {
        return \Response::success(auth()->logout());
    }
}
