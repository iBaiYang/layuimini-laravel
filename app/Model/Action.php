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
}
