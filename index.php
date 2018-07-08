<?php
require_once  "autoload.php";
require_once  "wlphp\url\URL.php";
//$config= require_once  "config\config.php";

$result_arr=wl_request_url();

//dd($result_arr);
$modules=$result_arr[0];       //得到模块
$controller=$result_arr[1];  //得到控制器
$action=$result_arr[2];         //得到方法

$namespace="\app\\$modules\\Controller\\$controller";

$namespace=$namespace."Controller";

//ddd($namespace);
try{

    if (!class_exists($namespace)){
        error("调用的控制器不存在");
    };

    $con=new $namespace();
    if (!method_exists($con,$action)){
        error("调用不存在的方法");
    };

    $con->$action();
}catch (Exception $exception)
{
var_dump("发生异常!");

die();
}

//new \app\Controller\index();
