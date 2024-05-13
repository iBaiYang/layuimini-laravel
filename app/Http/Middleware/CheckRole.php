<?php

namespace App\Http\Middleware;

use App\Model\Action;
use Illuminate\Support\Facades\Route;
use Closure;

class CheckRole
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
        $route_name = Route::current()->getName();
        if (!(new Action())->checkAuth($route_name)) {
            abort(403, '你没有权限执行此操作');
        }

        return $next($request);
    }
}
