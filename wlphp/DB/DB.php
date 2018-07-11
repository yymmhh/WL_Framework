<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 11:30
 * 主要用于传入sql语句返回查询的结果，并且自动映射
 */

namespace wlphp\DB;


class DB
{
    public $mysqli = null;

    /**
     * 连接Mysql数据库
     */
    public function mysqlDb()
    {
        //导入配置不可以用require_once
        $config= require c()."/config/db.php";

        $host=$config['host'];
        $user=$config['user'];
        $name=$config['name'];
        $password=$config['password'];

//        $this->mysqli = new \mysqli('127.0.0.1', 'root', 'root', 'yii_book');

        $this->mysqli = new \mysqli($host, $user, $password, $name);

//        $this->mysqli->connect($host, $user, $password, $name);
        if ($this->mysqli->connect_error) {

            ddd($this->mysqli->connect_error);
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);

        }
    }

    /**新增操作
     * @param $name
     * @param $sql
     * @return int|string
     */
    public function IntDB($name, $sql)
    {
        $this->mysqlDb();

        if ($name == "update") {

            $result = $this->mysqli->query($sql);


            return $result;
        } elseif ($name == "create") {


            $result = $this->mysqli->query($sql);

            $id = mysqli_insert_id($this->mysqli);

            return $id;
        } elseif ($name == "delete") {

            $result = $this->mysqli->query($sql);


            return $result;

        }


    }


    /**查询这个表的所有列，返回的是一个数组
     * @param $name
     * @return array
     */
    public function columnDB($name)
    {
//        ddd($name);
        $this->mysqlDb();
        $name=strtolower($name);
        $filedSql = "SHOW FIELDS FROM $name";

        $filed = $this->mysqli->query($filedSql);

        $colomArr = [];
        while ($row = $filed->fetch_array(MYSQLI_ASSOC)) {

            $colomArr[] = $row["Field"];

        }
        return $colomArr;
    }

//查询一对多不重复的主列
    public function my($name,$where)
    {

        $sql = "select * from {$name} where 1=1 {$where}";
//        ddd($sql);
        $result = $this->mysqli->query($sql);
        $infoArr = [];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[] = $row;
        }
        return $infoArr;
    }


    public function findDB_has_many($name, $sql, $namespace, $has_name_arr,$where)
    {

        $this->mysqlDb();

        $new_arr="";
        $my_xin_arr=$this->my($name,$where);


        foreach ($has_name_arr as $item) {
            $new_arr[] = $this->my($item,$where);

        }


        $result = $this->mysqli->query($sql);
        $infoArr = [];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[] = $row;
        }




        $my_arr = [];

        foreach ($infoArr as $item) {


            foreach ($item as $key => $itemtwo) {

                $mode_Index = strrpos($key, '&');

                $mode_name = trim(substr($key, 0, $mode_Index));
                $mode_value = trim(substr($key, $mode_Index + 1));

                if ($mode_name == $name) {
                    $temp_my_arr[$mode_value] = $itemtwo;

                }
                foreach ($has_name_arr as $itemthree) {
                    if ($mode_name == $itemthree) {
                        $temp_my_arr[$itemthree][$mode_value] = $itemtwo;
                    }
                }


            }


            $my_arr[] = $temp_my_arr;


        }
//        ddd($my_arr);




        //双从循环实现一对多
        //第一层是select 本身 的所有信息
        foreach ($my_xin_arr as &$item) {
            $count=0;
            //这是sql查询出来的所有的信息
            foreach ($my_arr as $itemtwo) {

                //如果本身的id=sql查询出来的id
                if ($item['id'] == $itemtwo['id']) {            //判断的是没有重复的主列和查询出关联的主列
                    //然后拿到,加到主列里面
                            //关联的类的名称
                    foreach ($has_name_arr as $itemthree) {
                        if ($itemtwo[$itemthree]["id"] != null) {
                            $item[$itemthree][$itemtwo[$itemthree]["id"]] = $itemtwo[$itemthree];

                        } else {
                            $item[$itemthree] = null;
                        };
                    }

                }
            }

//            ddd($my_xin_arr);

        }

        return $my_xin_arr;
    }

    /**
     * 关联查询的数据库操作
     * @param $name //主数据库
     * @param $sql //sql语句
     * @param $namespace //
     * @param $has_name //关联的数据库
     * @return array
     */
    public function findDB_has_one($name, $sql, $namespace, $has_name)
    {
        $this->mysqlDb();

//        $colomArr=$this->columnDB($name);

        $result = $this->mysqli->query($sql);
        $infoArr = [];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[] = $row;
        }

        $my_arr = [];

        foreach ($infoArr as $item) {

            $temp_my_arr = [];
            $last_arr = [];
            $last_two_arr = [];

            foreach ($item as $key => $itemtwo) {

                $mode_Index = strrpos($key, '&');

                $mode_name = substr($key, 0, $mode_Index);
                $mode_value = substr($key, $mode_Index + 1);

                if ($mode_name == $name) {
                    $temp_my_arr[$mode_value] = $itemtwo;
                    $last_arr[$mode_value] = $itemtwo;

                }

                if ($mode_name == $has_name) {
                    $temp_my_arr[$has_name][$mode_value] = $itemtwo;
                }

            }
            $last_two_arr = $last_arr;

            $my_arr[] = $temp_my_arr;


        }


        return $my_arr;


    }

    /**
     * 查询个数的数据库操作
     */

    public function findCountDB($name, $sql, $namespac){
        $this->mysqlDb();

        $colomArr = $this->columnDB($name);

        $result = $this->mysqli->query($sql);
        $infoArr = [];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[] = $row;
        }

        return $infoArr[0];

    }




    /**
     *查询的数据库操作
     * @param $name
     * @param $sql
     * @return array
     */
    public function findDB($name, $sql, $namespace)
    {
        $this->mysqlDb();


        $colomArr = $this->columnDB($name);

        $result = $this->mysqli->query($sql);
        $infoArr = [];

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {//mysql_fetch_array从结果集中取得一行作为关联数组或者数字数组。

            $infoArr[] = $row;
        }

        //生成数组
        $tempList = [];


        //外层循环控制数据
        foreach ($infoArr as $key => $item) {

            $tempName = [];
            //内层循环控制列
            foreach ($colomArr as $colomItem) {
                $tempName[$colomItem] = $item[$colomItem];
            }
            $tempList[] = $tempName;
        }


//生成对象的方法
//        $tempList=[];

        //外层循环控制数据
//        foreach ($infoArr as $key=>$item)
//        {
//
//            $tempName=new $namespace();   //new 出来是个命名空间
//            //内层循环控制列
//            foreach ($colomArr as $colomItem)
//            {
//                $name="name";
////                 $tempName->name="123";
//                try
//                {
//                    /**
//                     * 有错不报了
//                     */
//                   @$tempName->$colomItem=$item[$colomItem];
//                }catch (Error $error){
//
//                    throw new Exception();
//                }
//
//            }
//            $tempList[]=$tempName;
//        }

        return $tempList;
    }

    //PDD方法连接数据库
    public function pdd()
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