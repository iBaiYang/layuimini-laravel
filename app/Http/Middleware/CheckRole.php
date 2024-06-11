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

        // 体验环境，不修改数据
        if ($request->route()->getName() != 'admin_login_out') {
            if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
                return response(json_encode([
                    'code' => 0,
                    'msg' => '成功',
                    'data' => []
                ]), 200, ['Content-Type' => 'text/html',]);
            }
        }

        return $next($request);
    }
}
