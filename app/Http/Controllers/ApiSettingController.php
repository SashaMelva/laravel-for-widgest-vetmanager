<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewApiSetting;
use App\Http\Service\UserApiSettings;
use App\Models\ApiSetting;
use App\Providers\RouteServiceProvider;
use Auth;
use Exception;

class ApiSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();

        return view(
            'api-setting/api-setting',
            [
                'apiSetting' => [$apiSetting->key, $apiSetting->url]
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('api-setting/add-api-setting');
    }

    /**
     * Store a newly created resource in storage.
     */
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
