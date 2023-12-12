<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class CheckLogin
 * @package App\Http\Middleware
 */
class CheckLogin
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
        if (!$request->session()->get('user_token')) {
            return redirect(route('admin_login'));
        }

        return $next($request);
    }
}
