<?php

namespace App\Http\Controllers\Admin;

use App\Model\Action;
use App\Model\Role;
use Illuminate\Http\Request;

/**
 * Class RbacController
 * @package App\Http\Controllers\Admin
 */
class RbacController extends CommonController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action(Request $request)
    {
        return view('admin.rbac.action');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function action_api(Request $request)
    {
        $records = Action::query()->whereIn('status', [1, 2])
            ->orderBy('sort', 'asc')
            ->get()->toArray();

        $total = Action::query()->whereIn('status', [1, 2])->count();

        return $this->ret([
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $records
        ]);
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function action_edit(Request $request)
    {
        try {
            $id = $request->get('id');
            $record = $id ? Action::query()->where('id', $id)->firstOrFail() : [];
            if ($request->isMethod("POST")) {
                $data = $request->all();

                $updated_at = time();
                if (empty($record)) {
                    $record = new Action();
                    $record->created_at = $updated_at;
                }

                $record->pid = $data['pid'];
                $record->type = $data['type'];
                $record->title = $data['title'];
                $record->icon = $data['icon'] ?? '';
                $record->target = $data['target'];
                $record->href = $data['href'] ?? '';
                $record->sort = $data['sort'];
                $record->status = $data['status'];
                $record->updated_at = $updated_at;

                $record->save();
//                $record ? $record->update($data) : Action::query()->create($data);

                return $this->ret([
                    'code' => 1,
                    'msg' => '操作成功',
                ]);
            }

            $menus = Action::query()->whereIn('type', [1, 2])
                ->whereIn('status', [1, 2])
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

            return view('admin.rbac.action_edit', compact('menus', 'record'));
        } catch (\Exception $ex) {
            return $this->ret(['status' => 0, 'msg' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function role(Request $request)
    {
        return view('admin.rbac.role');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function role_api(Request $request)
    {
        $data = $request->all();
        $page = $data['limit'] ?? 10;

        $model = Role::query()->whereIn('status', [Role::STATUS_ENABLE, Role::STATUS_DISABLE]);

        if ($data['searchParams'] ?? '') {
            $searchParams = json_decode($data['searchParams'], true);
            if ($searchParams['name'] ?? '') {
                $model->where('name', 'like', "%{$searchParams['name']}%");
            }

            if ($searchParams['status'] ?? '') {
                $model->where('status', $searchParams['status']);
            }

            if (!empty($searchParams['created_at'])) {
                $datetime = explode('~', $searchParams['created_at']);
                $model->where('created_at', '>=', strtotime($datetime[0]))->where('created_at', '<=', strtotime($datetime[1]));
            }
        }

        $records = $model->orderBy('sort', 'asc')
            ->orderByDesc('id')
            ->paginate($page)->toArray();

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
    public function role_edit(Request $request)
    {
        try {
            $id = $request->get('id');
            $record = $id ? Role::query()->where('id', $id)->firstOrFail() : [];
            if ($request->isMethod("POST")) {
                $data = $request->all();

                $updated_at = time();
                if (empty($record)) {
                    $record = new Role();
                    $record->created_at = $updated_at;
                }

                $status = Role::STATUS_ENABLE;
                if (empty($data['status']) || $data['status'] == Role::STATUS_DISABLE) {
                    $status = Role::STATUS_DISABLE;
                }

                $record->name = $data['name'];
                $record->sort = $data['sort'];
                $record->status = $status;
                $record->updated_at = $updated_at;

                $record->save();

                return $this->succ('操作成功');
            }

            return view('admin.rbac.role_edit', compact('record'));
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function role_delete(Request $request)
    {
        try {
            if ($request->isMethod("POST")) {
                $ids = $request->get('ids');
                if (!$ids ?? '') {
                    throw new \Exception('未选择账号');
                }
                $ids = json_decode($ids, true);

                $total = Role::query()->whereIn('id', $ids)->whereIn('status', [Role::STATUS_ENABLE, Role::STATUS_DISABLE])->count();
                if (count($ids) != $total) {
                    throw new \Exception('提交参数有误');
                }

                $ret = Role::query()->whereIn('id', $ids)->update(['status' => Role::STATUS_DELETE, 'updated_at' => time()]);
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
