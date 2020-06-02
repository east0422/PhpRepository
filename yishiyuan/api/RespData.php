<?php

header('Content-Type:text/html;charset=utf-8');

/* 
 * 数据返回类
 */
class RespData {

    public function __construct(){

    }

    // 成功返回data
    public static function successData($data){
        $resp = array(
            'status' => 200,
            'data' => $data,
            'message' => '成功',
        );
        echo json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die;
    }

    // 成功返回message
    public static function success($message){
        $resp = array(
            'status' => 200,
            'message' => $message,
        );
        echo json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die;
    }

    // 错误返回message
    public static function error($message){
        $resp = array(
            'status' => 500,
            'message' => $message,
        );
        echo json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die;
    }
}
