<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class CommonController
 * @package App\Http\Controllers\Admin
 */
class CommonController extends Controller
{
    /**
     * @param array $data
     * @return false|string
     */
    public function ret($data = [])
    {
        return json_encode(array_merge([
            'code' => 0,
            'msg' => '',
            'data' => []
        ], $data));
    }
}
