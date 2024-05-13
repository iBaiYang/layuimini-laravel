<?php

namespace App\Http\Controllers\Admin;

use App\Model\Action;
use App\Model\Admin;
use App\Model\AdminRole;
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
        $records = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->get()->toArray();

        $total = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])->count();

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

            $menus = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                ->where(function($query) {
                    $query->where('pid', 0)
                        ->orWhereIn('type', [Action::TYPE_CATALOG, Action::TYPE_MENU]);
                })
                ->orderBy('pid', 'asc')
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

            $pid_0_action = [];
            foreach ($menus as $menu) {
                if ($menu['pid'] == 0 && $menu['type'] == Action::TYPE_ACTION) {
                    $pid_0_action[] = $menu['id'];
                }
            }
            $actions_1 = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                ->WhereIn('pid', $pid_0_action)
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

            $menus = array_merge($menus, $actions_1);

            return view('admin.rbac.action_edit', compact('menus', 'record'));
        } catch (\Exception $ex) {
            return $this->ret(['status' => 0, 'msg' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function action_delete(Request $request)
    {
        try {
            if ($request->isMethod("POST")) {
                $id = $request->get('id');
                if (!$id ?? '') {
                    throw new \Exception('未选择操作');
                }

                $record = $id ? Action::query()->where('id', $id)
                    ->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                    ->firstOrFail() : [];

                ///////////每个角色中包含该操作及其子操作的都重新给角色操作赋值//////////
                $sons = (new Action())->getSons([$id], [Action::STATUS_ENABLE, Action::STATUS_DISABLE], [
                    Action::TYPE_CATALOG, Action::TYPE_MENU, Action::TYPE_ACTION
                ], true);

                $son_ids = [];
                if ($sons) {
                    foreach ($sons as $one) {
                        $son_ids[] = $one['id'];
                    }
                }
                array_push($son_ids, $id);

                // 若父级只包括当前操作，则取消父级
                if ($record->pid) {
                    $parent_one = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                        ->where('id', $record->pid)
                        ->first()->toArray();

                    $parent_one_son = (new Action())->getSons([$parent_one['id']], [Action::STATUS_ENABLE, Action::STATUS_DISABLE], [
                        Action::TYPE_CATALOG, Action::TYPE_MENU, Action::TYPE_ACTION
                    ], false);
                    if (count($parent_one_son) == 1) {
                        array_push($son_ids, $parent_one['id']);

                        if ($parent_one['pid']) {
                            $parent_two = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                                ->where('id', $parent_one['pid'])
                                ->first()->toArray();

                            $parent_two_son = (new Action())->getSons([$parent_two['id']], [Action::STATUS_ENABLE, Action::STATUS_DISABLE], [
                                Action::TYPE_CATALOG, Action::TYPE_MENU, Action::TYPE_ACTION
                            ], false);
                            if (count($parent_two_son) == 1) {
                                array_push($son_ids, $parent_two['id']);

                                if ($parent_two['pid']) {
                                    $parent_three = Action::query()->whereIn('status', [Action::STATUS_ENABLE, Action::STATUS_DISABLE])
                                        ->where('id', $parent_two['pid'])
                                        ->first()->toArray();

                                    $parent_three_son = (new Action())->getSons([$parent_three['id']], [Action::STATUS_ENABLE, Action::STATUS_DISABLE], [
                                        Action::TYPE_CATALOG, Action::TYPE_MENU, Action::TYPE_ACTION
                                    ], false);

                                    if (count($parent_three_son) == 1) {
                                        array_push($son_ids, $parent_three['id']);
                                    }
                                }
                            }
                        }
                    }
                }

                $roles = Role::query()->get();
                foreach ($roles as $one) {
                    if (!empty($one->action_ids)) {
                        $action_ids = explode(',', $one->action_ids);
                        $action_ids = array_diff($action_ids, $son_ids);
                        $action_ids = implode(',', $action_ids);

                        $one->action_ids = $action_ids;
                        $one->updated_at = time();
                        $one->save();
                    }
                }

                $record->status = Action::STATUS_DELETE;
                $record->updated_at = time();
                $ret = $record->save();
                if (!$ret) {
                    throw new \Exception('删除失败');
                }

                return $this->succ('操作成功');
            }

            throw new \Exception('非POST方式提交');
        } catch (\Exception $ex) {
            return $this->fail($ex->getLine() . $ex->getMessage());
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

                AdminRole::query()->whereIn('role_id', $ids)->delete();

                return $this->succ('操作成功');
            }

            throw new \Exception('非POST方式提交');
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function role_actions(Request $request)
    {
        try {
            $id = $request->get('id');
            $record = $id ? Role::query()->where('id', $id)->firstOrFail() : [];

            if ($request->isMethod("POST")) {
                $data = $request->all();

                $action_ids = '';
                if (!empty($data['action_ids'])) {
                    $pids_1 = Action::query()->whereIn('id', $data['action_ids'])->get()->pluck('pid')->toArray();
                    $pids_2 = Action::query()->whereIn('id', $pids_1)->get()->pluck('pid')->toArray();
                    $pids = array_merge($pids_1, $pids_2);
                    $pinfo = Action::query()->whereIn('id', $pids)->get()->toArray();
                    $action_ids = implode(',', array_filter(array_unique(array_merge($data['action_ids'], array_column($pinfo, 'id'), array_column($pinfo, 'pid')))));
                }

                $record->action_ids = $action_ids;
                $record->updated_at = time();
                $record->save();

                return $this->succ('操作成功');
            }

            $action_ids = explode(',', $record['action_ids']);

            $catalogs = Action::query()->where('pid', 0)
                ->where('status', Action::STATUS_ENABLE)
                ->orderBy('pid', 'asc')
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();
            $actions1 = [];
            $catalog_ids = [];
            foreach ($catalogs as $catalog) {
                $actions1[$catalog['id']] = $catalog;
                $catalog_ids[] = $catalog['id'];
            }

            $menus = Action::query()
                ->where('status', Action::STATUS_ENABLE)
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();
            $actions2 = [];
            foreach ($menus as $menu) {
                if (in_array($menu['pid'], $catalog_ids)) {
                    $actions1[$menu['pid']]['child'][] = $menu;
                } else {
                    $actions2[$menu['pid']]['child'][] = $menu;
                }
            }

            $actions = Action::query()->where('type', 3)
                ->where('status', Action::STATUS_ENABLE)
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();
            $actions3 = [];
            foreach ($actions as $action) {
                $actions3[$action['pid']][] = $action;
            }

            return view('admin.rbac.role_actions', compact('record', 'actions1', 'actions2', 'actions3', 'action_ids'));
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function role_users(Request $request)
    {
        try {
            $id = $request->get('id');
            $record = $id ? Role::query()->where('id', $id)->firstOrFail() : [];

            if ($request->isMethod("POST")) {
                $data = $request->all();

                if ($data['type'] == 'leave') {
                    $admin_ids = explode(',', trim($data['ids'], ','));
                    AdminRole::query()->where('role_id', $data['id'])->whereIn('admin_id', $admin_ids)->delete();
                } else {
                    $insert_datas = [];
                    foreach (explode(',', trim($data['ids'], ',')) as $admin_id) {
                        array_push($insert_datas, [
                            'role_id' => $id,
                            'admin_id' => $admin_id,
                            'created_at' => time(),
                        ]);
                    }

                    AdminRole::query()->insert($insert_datas);
                }

                return $this->succ('操作成功');
            }

            $users = [];
            $admin_users = Admin::query()->where('status', Admin::STATUS_ENABLE)
                ->where('username', '!=', 'admin')
                ->orderBy('id', 'asc')
                ->get()->keyBy('id');
            foreach ($admin_users as $one) {
                array_push($users, [
                    'value' => $one['id'],
                    'title' => $one['username'],
                ]);
            }

            $role_users = [];
            foreach (AdminRole::query()->where('role_id', $id)->get() as $one) {
                array_push($role_users, $one['admin_id']);
            }

            return view('admin.rbac.role_users', compact('record', 'users', 'role_users'));
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage());
        }
    }
}
