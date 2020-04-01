<?php
date_default_timezone_set('PRC');
/**
 * Created by PhpStorm.
 * User: Freedom
 * Date: 2020/04/01
 * Time: 17:47
 */

/**
 * 公用的方法 返回json数据，进行信息的提示
 * @param $status 状态
 * @param string $message 提示信息
 * @param array $data 返回数据
 */
function showMsg($status,$message = '',$data = array()){
    $result = array(
        'status' => $status,
        'message' =>$message,
        'data' =>$data
    );
    exit(json_encode($result));
}

/**
 * 生成sid(short)
 * @return string
 */
function generateSid() {
    mt_srand((double)microtime() * 10000);
    $charid = md5(uniqid(rand(), true));
    $hyphen = chr(45);
    $sid    = substr($charid, 0, 4) . $hyphen
        . substr($charid, 6, 9);
    return $sid;
}

/**
 * 生成uuid(long)
 * @return string
 */
function generateUuid()
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid  = substr ( $chars, 0, 8 ) . '-'
        . substr ( $chars, 8, 4 ) . '-'
        . substr ( $chars, 12, 4 ) . '-'
        . substr ( $chars, 16, 4 ) . '-'
        . substr ( $chars, 20, 12 );
    return $uuid ;
}

/**
 * 生成序列号(日期)
 * @return string
 */
function createSerial() {
    return date('YmdHis') . mt_rand(1000, 9999);
}

/**
 * 返回当前时间
 * @return string
 */
function getNowTime() {
    return date('Y-m-d H:i:s', time() + 8 * 3600);
}

/**
 * 创建appid
 * @return string
 */
function createAppId() {
    return 'app' . substr(strtolower(md5(Util::createNonceStr(15))), 0, 12);
}

/**
 * 生成随机字符串
 * @param int $length
 * @return string
 */
function createNonceStr($length = 32) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str   = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * 获取ip地址
 * @return string
 */
function getIp() {
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR")) {
        $ip = getenv("REMOTE_ADDR");
    } else {
        $ip = false;
    }
    $realip = $ip;
    $realip = explode(',', $realip);
    $cIP    = $realip[0];
    return $cIP ? $cIP : '0.0.0.0';
}

/**
 * 生成随机字符串
 * @param int $limit
 * @return string
 */
function generateKey($limit = 7) {
    $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    srand((double)microtime() * 1000000);
    $rand = "";
    for ($i = 0; $i < $limit; $i++) {
        $rand .= $c[rand() % strlen($c)];
    }
    return $rand;
}

/**
 * 对象转数组
 * @param obj $obj
 * @return array
 */
function object_to_array($obj) {
    $arr = (array)$obj;
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $arr[$k] = object_to_array($v);
        }
    }

    return $arr;
}
