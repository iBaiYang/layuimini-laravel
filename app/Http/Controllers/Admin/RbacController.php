<?php

namespace App\Http\Controllers\Admin;

use App\Model\Action;
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

            $parent_ids = Action::query()->where(['pid' => 0])->pluck('id');
            $menus = Action::query()->whereIn('type', [1, 2])
                ->whereIn('id', $parent_ids)
                ->orWhereIn('pid', $parent_ids)
                ->orderByDesc('pid')->get();

            return view('admin.rbac.action_edit', compact('menus', 'record'));
        } catch (\Exception $ex) {
            return $this->ret(['status' => 0, 'msg' => $ex->getMessage()]);
        }
    }
}
