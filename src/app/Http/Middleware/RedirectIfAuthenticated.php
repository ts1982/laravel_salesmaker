<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // $redirect_url = '/';

        // if ($guard == 'admins') {
        //     $redirect_url = '/dashboard';
        // }

        if (Auth::guard($guard)->check()) {
            if ($guard == 'admins') {
                return redirect('/dashboard/login');
            }
            return redirect('/');
        }

        return $next($request);
    }
}
