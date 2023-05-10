<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewApiSetting;
use App\Models\ApiSetting;
use App\Providers\RouteServiceProvider;
use Auth;

class ApiSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(
            'api-setting/api-setting',
            [
                'apiSetting' => ApiSetting::all()->where('user_id', Auth::user()->id)
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
