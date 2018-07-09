<?php
/**
 * Created by PhpStorm.
 * User: 031
 * Date: 2018/7/4
 * Time: 16:30
 */

namespace app\admin\Controller;

use app\index\Model\Post;
use app\index\Model\User;
use app\index\Model\User_Info;
use app\index\Model\Zan;
use wlphp\DB\Test;

class IndexController
{

    public function __construct()
    {
        echo "admin";
    }


    public  function index(){
        echo config("username");
    }

    public function find()
    {
//        $zans= Zan::all()->hasMany(User::class,["id","user_id"]);
        $post = Post::all()->count();
//        $post=User::all()->get();
//
        dd($post);
    }
}