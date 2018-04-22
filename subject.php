<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 10:58
 */
require_once "Model/Model.php";
class subject extends  Model
{
      public function  go()
      {
          $sql = subject::all()
              ->where(['SubjectNo>'=>"1",'gradeid='=>'1'])
              ->where(['ClassHour!='=>'36'])
              ->order('SubjectNo','desc')
              ->limt('3','1')
              ->get();
          foreach ($sql as $item)
          {
            var_dump($item);
            echo "<br>";
          }
      }

      public function go2()
      {
        $sql=subject::all()->get();
        var_dump($sql);
      }


}

$t=new subject();

$t->go2();