<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewApiSetting;
use App\Models\ApiSetting;
use App\Providers\RouteServiceProvider;
use Auth;

class ApiSettingController extends Controller
{
    public function viewApiData()
    {
        return view('api-setting', ['apiSetting' => ApiSetting::all()->where('user_id', Auth::user()->id)]);
    }

    public function viewRegisterSettingApi()
    {
        return view('add-api-setting');
    }

    public function store(StorePostNewApiSetting $request)
    {
        $valid = $request->validated();

        $flight = new ApiSetting();
        $flight->key = $valid['apiKey'];
        $flight->url = $valid['domainName'];

        $flight->user_id = Auth::user()->id;

        $flight->save();

        return redirect(RouteServiceProvider::HOME);
    }
}
