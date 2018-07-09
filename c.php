<?php



function c(){

    return __DIR__;

}


function config($key=null){
    $config= require c()."\config\config.php";
    if(empty($key)){
        return "key不存在!";
    }
    return $config[$key];
}