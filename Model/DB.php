<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 11:30
 * 主要用于传入sql语句返回查询的结果，并且自动映射
 */

class DB
{
    public  $mysqli=null;

    /**
     * 连接Mysql数据库
     */
    public  function mysqlDb()
    {

        $this->mysqli = new mysqli('127.0.0.1', 'root', 'ok', 'accp2');

        if ( $this->mysqli->connect_error) {

            die('Connect Error (' .  $this->mysqli->connect_errno . ') ' .  $this->mysqli->connect_error);

        }
    }

    /**新增操作
     * @param $name
     * @param $sql
     * @return int|string
     */
    public function IntDB($name,$sql)
    {
        $this->mysqlDb();

        if($name=="update")
        {

            $result =$this->mysqli->query($sql);



            return $result;
        }elseif ($name=="create")
        {


            $result =$this->mysqli->query($sql);

            $id= mysqli_insert_id($this->mysqli);

            return $id;
        }elseif ($name=="delete")
        {

            $result =$this->mysqli->query($sql);



            return $result;

        }


    }


    /**查询这个表的所有列，返回的是一个数组
     * @param $name
     * @return array
     */
    public function columnDB($name)
    {
        $this->mysqlDb();
        $filedSql="SHOW FIELDS FROM $name";
        $filed =$this->mysqli->query($filedSql);

        $colomArr=[];
        while ($row = $filed->fetch_array(MYSQLI_ASSOC)){

            $colomArr[]=$row["Field"];

        }
        return $colomArr;
    }


    /**
     *查询的数据库操作
     * @param $name
     * @param $sql
     * @return array
     */
    public function  findDB($name,$sql)
    {
        $this->mysqlDb();



        $colomArr=$this->columnDB($name);

        $result =$this->mysqli->query($sql);
        $infoArr=[];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[]=$row;
        }


        //生成数组
//        $tempList=[];
//
//        //外层循环控制数据
//        foreach ($infoArr as $key=>$item)
//        {
//
//            $tempName=[];
//            //内层循环控制列
//            foreach ($colomArr as $colomItem)
//            {
//                $tempName[$colomItem]=$item[$colomItem];
//            }
//            $tempList[]=$tempName;
//        }





//生成对象的方法
        $tempList=[];

        //外层循环控制数据
        foreach ($infoArr as $key=>$item)
        {

            $tempName=new $name();
            //内层循环控制列
            foreach ($colomArr as $colomItem)
            {
                $name="name";
//                 $tempName->name="123";
                try
                {
                    /**
                     * 有错不报了
                     */
                   @$tempName->$colomItem=$item[$colomItem];
                }catch (Error $error){

                    throw new Exception();
                }

            }
            $tempList[]=$tempName;
        }

        return $tempList;
    }

    //PDD方法连接数据库
    public  function pdd()
    {
        try {
            $PDO = new PDO('mysql:host=127.0.0.1;dbname=accp2', 'root', 'ok');
            $result = $PDO->query('select * from ymh');
            $row = $result->fetch(PDO::FETCH_ASSOC);
            print_r($row);

            // 关闭mysqi连接
            $PDO = null;
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
}