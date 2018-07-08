<?php
/**
 * Created by PhpStorm.
 * User: Root
 * Date: 2018/7/8
 * Time: 19:07
 * 处理加载的URL
 */



     function wl_request_url()
    {
         $url_params=$_SERVER['REQUEST_URI'];

        $arr = explode("/",$url_params);


        //出除参数如果加了index.php 必须先先判断 因为0==false
        $index_key=array_search("index.php" ,$arr);

        if($index_key!=false){
            array_splice($arr,$index_key,1);
        }

        //出除参数的第一个空格
        $key=array_search("" ,$arr);
        array_splice($arr,$key,1);


        return $arr;
    }


