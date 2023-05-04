<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostNewUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(StorePostNewUser $request): RedirectResponse
    {
        $valid = $request->validated();

        $user = User::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'password' => Hash::make($valid['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

//        Auth::user()->apiSetting;
//        if (isset(Auth::user()->apiSetting->key)) {
//            return redirect(RouteServiceProvider::HOME);
//        }

        return redirect()->route('add-api-setting');
    }
}
