<?php

function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/' . $path . '.php';

//    echo "<hr>"; var_dump("加载的类{$file}"); echo "<hr>";
//    echo "<br>";
    if (file_exists($file)) {
        require_once $file;
    }


}
spl_autoload_register('classLoader');

function dd($arr){
    echo "<pre>";

    var_dump($arr);

}

function error($arr){
    echo "<pre>";

    echo "<h2 style='background-color: red;color: aliceblue;height: 100px'>{$arr} </h2>";
    die();

}


function json($msg = "", $type = 1, $is_end = true){
    $json['status'] = $type;
    if (is_array($msg)) {
        foreach ($msg as $key => $v) {
            if ($v === null) $v = '';
            $json[$key] = $v;
        }
    } elseif (!empty($msg)) {
        $json['message'] = $msg;
    }
    if ($is_end) {
        echo json_encode($json);
        exit;
    } else {
        echo json_encode($json);
        exit;
    }
}


function jsonSuccess($data = [], $message = '', $code = 0, $share = array())
{
    header('Content-Type: application/json');
    $message = $message ? $message : '调用成功';
    jsonEncode(true, $data, $message, $code, $share);
}


function jsonEncode($status, $data = [], $message = '', $code = 0)
{

    $status = boolval($status);
    $data = $data ? $data : (object) array();
    $message = strval($message);
    $code = intval($code);
    $result = [
        'status' => $status,
        'code' => $code,
        'message' => $message,
        'data' => $data
    ];

    echo json_encode($result);
}