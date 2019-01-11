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
use Mpdf\Mpdf;

use wlphp\DB\Model;


class IndexController
{

    public function __construct()
    {
    }


    public  function index(){
        echo config("username");
    }

    public function find()
    {

        $post=new Post();
        $info=$post
            ->join("left JOIN USER ON post.`user_id`=user.`id`")
            ->join("INNER JOIN bjyadmin_users ON post.`user_id`=`bjyadmin_users`.`id`")
            ->where(['post.id>'=>1])
            ->order('post.id','desc')
            ->limt(2,10)
            ->get();
        dd($info);

    }
}