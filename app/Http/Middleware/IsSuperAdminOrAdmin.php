<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsSuperAdminOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if ((Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin') && Auth::user()->status == 'active') {
                return $next($request);
            }
        }
        return redirect(route('login'));
    }
}
