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
}
