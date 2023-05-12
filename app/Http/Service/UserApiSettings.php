<?php

namespace App\Http\Service;

use App\Models\ApiSetting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserApiSettings
{
    public function getApiSetting(): ApiSetting
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new Exception('Model getting error Users');
        }

        return $user->apiSetting;
    }
}
