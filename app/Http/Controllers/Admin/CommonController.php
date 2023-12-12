<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Class CommonController
 * @package App\Http\Controllers\Admin
 */
class CommonController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @return false|string
     */
    public function ret($data = [])
    {
        return json_encode(array_merge([
            'code' => 0,
            'msg' => 0,
            'data' => []
        ], $data));
    }
}
