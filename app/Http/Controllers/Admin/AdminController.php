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

        $model = Admin::query()->whereIn('status', [Admin::STATUS_ENABLE, Admin::STATUS_DISABLE])->where('username', '!=', 'Admin');

        if ($data['searchParams'] ?? '') {
            $searchParams = json_decode($data['searchParams'], true);
            if ($searchParams['username'] ?? '') {
                $model->where('username', 'like', "%{$searchParams['username']}%");
            }

            if ($searchParams['status'] ?? '') {
                $model->where('status', $searchParams['status']);
            }

            if (!empty($searchParams['created_at'])) {
                $datetime = explode('~', $searchParams['created_at']);
                $model->where('created_at', '>=', strtotime($datetime[0]))->where('created_at', '<=', strtotime($datetime[1]));
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
                } else {
                    if ($record->status == Admin::STATUS_DELETE) {
                        throw new \Exception('该账户不可编辑');
                    }
                }

                $status = Admin::STATUS_ENABLE;
                if (empty($data['status']) || $data['status'] == Admin::STATUS_DISABLE) {
                    $status = Admin::STATUS_DISABLE;
                }

                $record->username = $data['username'];
                $record->mobile = $data['mobile'] ?? '';
                $record->email = $data['email'] ?? '';
                $record->remark = $data['remark'] ?? '';
                $record->status = $status;
                $record->updated_at = $updated_at;

                $record->save();

                return $this->succ('操作成功');
            }

            return view('admin.admin.admin_edit', compact('record'));
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function admin_delete(Request $request)
    {
        try {
            if ($request->isMethod("POST")) {
                $ids = $request->get('ids');
                if (!$ids ?? '') {
                    throw new \Exception('未选择账号');
                }
                $ids = json_decode($ids, true);

                $total = Admin::query()->whereIn('id', $ids)->whereIn('status', [Admin::STATUS_ENABLE, Admin::STATUS_DISABLE])->count();
                if (count($ids) != $total) {
                    throw new \Exception('提交参数有误');
                }

                $ret = Admin::query()->whereIn('id', $ids)->update(['status' => Admin::STATUS_DELETE, 'updated_at' => time()]);
                if (!$ret) {
                    throw new \Exception('删除失败');
                }

                return $this->succ('操作成功');
            }

            throw new \Exception('非POST方式提交');
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }
}
