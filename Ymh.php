<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 19:12
 *
 *
 *
 * */
 require_once "Model/Model.php";
 class Ymh extends Model
{
     public function go2()
     {
         $sql=Ymh::delete(["id"=>'1']);
         var_dump($sql);
     }
}

$t=new Ymh();
 $t->go2();