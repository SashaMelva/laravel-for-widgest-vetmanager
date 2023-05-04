<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiSettingsIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (isset(Auth::user()->apiSetting->key)) {
            return redirect(RouteServiceProvider::HOME);
        }

        //return redirect()->route('add-api-setting');

        return $next($request);
    }
}
