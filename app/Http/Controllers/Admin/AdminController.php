<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;

/**
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends CommonController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function admin(Request $request)
    {
        return view('admin.admin.admin');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function admin_api(Request $request)
    {
        $data = $request->all();
        $page = $data['limit'] ?? 10;

        $model = Admin::query()->whereIn('status', [1, 2])->where('username', '!=', 'Admin');
        if ($data['searchParams'] ?? '') {
            $searchParams = json_decode($data['searchParams'], 1);
            if ($searchParams['username'] ?? '') {
                $model->where('username', 'like', "%{$searchParams['username']}%");
            }
            if ($searchParams['c_time'] ?? '') {
                $datetime = explode('~', $searchParams['c_time']);
                $model->where('c_time', '>=', $datetime[0])->where('c_time', '<=', $datetime[1]);
            }
        }

        $records = $model->orderByDesc('id')->paginate($page)->toArray();

        return $this->ret([
            'code' => 0,
            'msg' => '',
            'count' => $records['total'],
            'data' => $records['data']
        ]);
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function admin_edit(Request $request)
    {
        try {
            $id = $request->get('id');
            $record = $id ? Admin::query()->where('id', $id)->firstOrFail() : [];

            if ($request->isMethod("POST")) {
                $data = $request->all();
                if (!$data['username'] ?? '') {
                    throw new \Exception('账户不能为空');
                }
                if (!$id && Admin::query()->where('username', $data['username'])->exists()) {
                    throw new \Exception('账户已存在');
                }
                if ($id && Admin::query()->where('username', $data['username'])->where('id', '!=', $id)->exists()) {
                    throw new \Exception('账户已存在');
                }

                $updated_at = time();
                if (empty($record)) {
                    $record = new Admin();
                    $record->created_at = $updated_at;

                    if (empty($data['password']) || !preg_match('/^[a-zA-Z0-9]{6,12}$/', $data['password'])) {
                        throw new \Exception('密码错误(支持字母、数字，8-12位)');
                    }
                    $record->password = md5($data['password']);
                }

                $record->username = $data['username'];
                $record->mobile = $data['mobile'] ?? '';
                $record->email = $data['email'] ?? '';
                $record->remark = $data['remark'] ?? '';
                $record->status = !empty($data['status']) ? 1 : 2;
                $record->updated_at = $updated_at;

                $record->save();

                return $this->ret([
                    'code' => 1,
                    'msg' => '操作成功',
                ]);
            }

            return view('admin.admin.admin_edit', compact('record'));
        } catch (\Exception $ex) {
            return $this->ret(['status' => 0, 'msg' => $ex->getMessage()]);
        }
    }
}
