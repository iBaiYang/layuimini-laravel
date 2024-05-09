<?php

function checkAuth($route) {
    return (new \App\Model\Action())->checkAuth($route);
}

if (!function_exists('encry')) {
    function encry($data, $encode = false, $encode_method = 'urlencode') {
        $key = hash('sha256', 'Wa0g!3#7*7');
        $enc = base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, hexToStr(md5('www.lz.com'))));
        return $encode ? $encode_method($enc) : $enc;
    }
}

if (!function_exists('decry')) {
    function decry($data) {
        $key = hash('sha256', 'Wa0g!3#7*7');
        return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, hexToStr(md5('www.lz.com')));
    }
}

if (!function_exists('hexToStr')) {
    function hexToStr($hex) {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
}

if (!function_exists('imgContentCompress')) {
    function imgContentCompress($src) {
        $max = 300; //图片允许最大尺寸
        list($width, $height) = getimagesize($src);
        $content = file_get_contents($src);
        $image = imagecreatefromstring($content);
        $new_width = $width;
        $new_height = $height;
        if ($width > $max) {
            $new_width = $max;
            $new_height = $height * $max / $width;
        } elseif ($height > $max) {
            $new_height = $max;
            $new_width = $width * $max / $height;
        }
        $image_thump = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($image_thump, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
        ob_start();
        imagejpeg($image_thump);
        $file_content = ob_get_contents();
        ob_end_clean();
        imagedestroy($image_thump);
        return $file_content;
    }

}

if (!function_exists('_trans')) {

    function _trans($msg) {
        $lang = env('APP_ENV') == 'production' ? 'en' : 'cn';
        $data = [
            'params_err' => ['cn' => '参数错误', 'en' => 'params error'],
            'no_auth' => ['cn' => '没有权限', 'en' => 'no auth'],
        ];
        return isset($data[$msg][$lang]) ? $data[$msg][$lang] : 'err';
    }

}


if (!function_exists('base_post')) {

    function base_post($url,$data) {
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }

}
