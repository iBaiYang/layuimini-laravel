<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 * @package App\Model
 */
class Action extends Model
{
    protected $table = 'll_action';

    protected $fillable = ['pid', 'type', 'title', 'icon', 'target', 'href', 'sort', 'status'];

    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
    const STATUS_DELETE = 3;

    const TYPE_CATALOG = 1;
    const TYPE_MENU = 2;
    const TYPE_ACTION = 3;

    /**
     * @param array $ids
     * @param array $status
     * @param array $type
     * @param bool $descendants
     * @return array|\Illuminate\Database\Eloquent\Collection|void|static[]
     */
    public function getSons($ids = [], $status = [], $type = [], $descendants = true)
    {
        $sons = self::query()->whereIn('pid', $ids)
            ->whereIn('status', $status)
            ->whereIn('type', $type)
            ->get()->toArray();

        if (!count($sons)) {
            return;
        }

        if ($descendants) {
            $son_ids = [];
            foreach ($sons as $one) {
                $son_ids[] = $one['id'];
            }

            $children = (new static)->getSons($son_ids, $status, $type, $descendants);
            if ($children) {
                $sons = array_merge($sons, $children);
            }
        }

        return $sons;
    }

    /**
     * @param array $ids
     * @param array $status
     * @param array $type
     * @param bool $parents
     * @return array|void
     */
    public function getParents($ids = [], $status = [], $type = [], $parents = true)
    {
        $records = self::query()->whereIn('id', $ids)
            ->whereIn('status', $status)
            ->whereIn('type', $type)
            ->get()->toArray();

        if (!count($records)) {
            return;
        }

        if ($parents) {
            $parent_ids = [];
            foreach ($records as $one) {
                $parent_ids[] = $one['pid'];
            }

            $data = (new static)->getParents($parent_ids, $status, $type, $parents);
            if ($data) {
                $records = array_merge($records, $data);
            }
        }

        return $records;
    }

    /**
     * @param $route
     * @return bool
     */
    public function checkAuth($route)
    {
        $admin_info = json_decode(session('admin_info'), true);
        if ($admin_info['username'] == 'admin') {
            return true;
        }

        $admin_id = $admin_info['id'];
        $role_ids = AdminRole::query()->where('admin_id', $admin_id)->get()->pluck('role_id');
        $action_ids = Role::query()->whereIn('id', $role_ids)
            ->where('status', Role::STATUS_ENABLE)
            ->get()->pluck('action_ids');

        $ids = [];
        foreach ($action_ids as $action_id) {
            $ids = array_merge($ids, explode(',', $action_id));
        }

        return self::query()->where('href', $route)
            ->where('status', self::STATUS_ENABLE)
            ->whereIn('id', $ids)->exists();
    }
}
