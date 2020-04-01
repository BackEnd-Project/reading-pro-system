<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpOption\None;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 输出成功
     */
    public function echoSuccess() {
        $this->echoJson(1, [ 'info' => 'success' ]);
    }

    /**
     * 输出失败
     */
    public function echoFail() {
        $this->echoJson(1, [ 'info' => 'fail' ]);
    }

    /**
     * 输出Json消息
     * @param int $code
     * @param mixed $msg
     */
    public function echoMsg($code, $msg = '') {
        $this->echoJson(Array(
            'retcode' => $code, 'retmsg' => $msg
        ));
        return true;
    }

    /**
     * @param mixed $arr
     * @return bool
     */
    public function echoJson($state = 1, $data = '') {
//        if ($arr['code'] === 0 && $arr && $this->nonce) {
//            $redis = mRedis::getInstance();
//            $redis->setex(DOO_PLATFORM_API_PREFIX . $this->nonce, 300, json_encode($arr, JSON_UNESCAPED_UNICODE));
//        }
        header('Content-Type: application/json; charset=utf-8');
        $json = [
            "state" => $state,
            "data"  => $data,
        ];
        // 去除uri反斜杠转义
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return true;
    }
}
