<?php
namespace Utils;

class ErrorThrower {

    public static function throw($msg, $data = []) {
        $response = new Response(Response::$STATUS_ERROR, $msg, Response::$HTTP_STATUS_BAD_REQUEST);
        $response->setData($data);
        $response->respond();
        exit(); 
    }
}