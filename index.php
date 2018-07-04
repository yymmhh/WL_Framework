<?php


$get=$_GET['c'];
$result = explode('/', $get);

$controller=$result[0];  //得到控制器
$action=$result[1];         //得到方法


require_once  "autoload.php";

$namespace="\app\Controller\\$controller";

$namespace=$namespace."Controller";

$con=new $namespace();

$con->$action();
//new \app\Controller\index();
