<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Model
 */
class Role extends Model
{
    protected $table = 'll_role';

    protected $fillable = ['name', 'action_ids', 'sort', 'status'];

    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
    const STATUS_DELETE = 3;
}
