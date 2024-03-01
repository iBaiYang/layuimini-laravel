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

    /**
     * @param string $msg
     * @param array $data
     * @return false|string
     */
    public function succ($msg = 'succ',$data = [])
    {
        return $this->ret([
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return false|string
     */
    public function fail($msg = 'fail', $data = [], $code = 1)
    {
        return $this->ret([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}
