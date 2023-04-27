<?php

namespace App\Http\Controllers;

use App\Models\ApiSetting;

class ApiController extends Controller
{
    public function viewApiData()
    {
        return view('api-setting', ['apiSetting' => ApiSetting::all()]);
    }
}
