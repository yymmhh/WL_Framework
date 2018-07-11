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
<<<<<<< HEAD
//        $zans= User::all()->hasMany(Post::class,["id","user_id"]);
//        $post = Post::all()->get();
=======
//        $zans= Zan::all()->hasMany(User::class,["id","user_id"]);
        $post = Post::all()->count();
>>>>>>> f63a481da75097b97d048809c54e1f81a8ad2d74
//        $post=User::all()->get();
//
//        dd($zans);
    }
}