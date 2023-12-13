<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;

/**
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends CommonController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {
        if (!$request->session()->get('admin_id')) {
            return redirect(route('admin_login'));
        }

        return redirect(route('admin_home'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        if ($request->session()->get('admin_id')) {
            return redirect(route('admin_home'));
        }

        return view('admin.index.login');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function login_in(Request $request)
    {
        if ($request->session()->get('admin_id')) {
            return $this->ret(['msg' => '请勿重复登录']);
        }

        try {
            $data = $request->all();
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';

            $admin_model = Admin::query()->where('username', $username)->first();
            if (!$admin_model) {
                throw new \Exception('账号密码错误');
            }
            if ($admin_model->password != md5($password)) {
                throw new \Exception('账号密码错误');
            }

            $request->session()->put('admin_id', $admin_model->id);
            $request->session()->put('admin_info', json_encode($admin_model->toArray()));

            return $this->ret([
                'code' => 1,
                'msg' => '登录成功',
            ]);
        } catch (\Throwable $ex) {
            return $this->ret(['msg' => $ex->getMessage()]);
        }
    }


}
