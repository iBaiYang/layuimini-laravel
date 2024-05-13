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
