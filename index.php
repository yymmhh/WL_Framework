<?php
require_once  "autoload.php";

if (empty($_GET)){
    error("参数没有带");
}

if (!array_key_exists("c", $_GET)){
    error("格式为index.php?c=控制器/方法");
};

$get=$_GET['c'];

$result = explode('/', $get);

$controller=$result[0];  //得到控制器
$action=$result[1];         //得到方法




$namespace="\app\Controller\\$controller";

$namespace=$namespace."Controller";

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
