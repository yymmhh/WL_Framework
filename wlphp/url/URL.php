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


    function request($params){
        $arr = explode("&",$params);
        $last=[];
        foreach ($arr as $item){
            $temp = explode("=",$item);
            $last[$temp[0]]=$temp[1];
        }

        return $last;
    }

    function get($key){
        $result_arr=wl_request_url();
        if(empty($result_arr[3])){
            return null;
        }
        $url=request($result_arr[3]);
        if(empty($url[$key])){
            return null;
        }
        return $url[$key];
    }

    function post($key){
        $url=$_POST;

        if(empty($url[$key])){
            return null;
        }
        return $url[$key];
    }



