<?php
/**
 * Created by PhpStorm.
 * User: 031
 * Date: 2018/7/4
 * Time: 16:30
 */
namespace app\Controller;
use app\Model\Zan;
use wlphp\DB\Test;

class IndexController
{

    public function __construct()
    {
//        echo __DIR__;

    }


    public function find(){
        $zans= Zan::find(1);

        dd($zans);
    }
}