<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 17:44
 */
namespace wlphp\DB;


class Model
{

    public static $name;
    public static $sql ="";
    public static $namespace="";



    /**
     * 为sql的表给名字
     */
    public function setName($name){

        Model::$name=$name;
        Model::$sql="select * from  $name where 1=1";

        $this->lian();
//        return $this;
    }

    //一个可有可无的连接件
    public function lian()
    {

//        var_dump($this);

        return $this;
    }

    //得到类,为了到DB里面new 然后生成
    function wl_get_class(){
        $namespace =get_called_class();
        $mode_Index=strrpos($namespace,'\\');
        $name=substr( $namespace,$mode_Index+1);

        self::$namespace=$namespace;
        self::$name=$name;
    }

    /**
     * Where条件
     */


    public  function  where($sql)
    {

        $tempConnectionSQl="";

        foreach ($sql as $key=>$item)
        {
//            var_dump($key."--".$item);
//            echo "<br>";
            $tempConnectionSQl=$tempConnectionSQl . ' and ' .$key."'$item'";
        }


        Model::$sql= Model::$sql .$tempConnectionSQl;

        return $this;
    }


    /**
     * 这个式最后的查询数据
     */
    public  function get()
    {

        echo Model::$sql;
        echo "<hr>";
        $db=new DB();
        return  $db->findDB(Model::$name,Model::$sql,Model::$namespace);

    }


    /**
     * 第一步的操作
     */
    public static function all()
    {

        self::wl_get_class();
//
        $arr= (new self())->setName(self::$name);
        return new Model();
    }


    /**
     * @param $column 想要查询的列
     * @param string $order 查询的方法 desc 默认式升序
     * @return $this 排序
     */
    public  function order($column,$order="")
    {
        $tempConnectionSQl=Model::$sql;


    $tempConnectionSQl=$tempConnectionSQl ." ORDER BY $column $order";
    Model::$sql=$tempConnectionSQl;
    return $this;
    }


    /**
     * 分页
     * @param $pageIndex 第几页
     * @param $pageSize 每页几个
     * @return $this
     */
    public function limt($pageIndex,$pageSize)
    {
        $pageIndex=$pageSize*($pageIndex-1);
        $tempConnectionSQl=Model::$sql;


        $tempConnectionSQl=$tempConnectionSQl ." LIMIT $pageIndex , $pageSize";
        Model::$sql=$tempConnectionSQl;

        return $this;

    }


    /**
     * 新增操作，传入一个宿主 ['name'=>'123'],返回0 和 1
     * @param $arr
     * @return mixed
     */
    public static function create($arr)
    {
        self::wl_get_class();
//
        $name=self::$name;

//        $arr= (new self())->setName(self::$name);
        $db=new DB();
        $columnArr= $db->columnDB($name);
        $columnString=implode(",",$columnArr);



        $columnKeys="";
        $columnVlaues="";
        foreach ($arr as $key=>$item)
        {
            $columnKeys=$columnKeys .$key . ",";
            $columnVlaues="$columnVlaues '$item'  ,";

        }
        $columnKeys=substr($columnKeys,0,strlen($columnKeys)-1);
        $columnVlaues=substr($columnVlaues,0,strlen($columnVlaues)-1);

        Model::$sql="insert into $name ($columnKeys) VALUES ($columnVlaues)";
        echo Model::$sql;
        echo "<hr>";
//        die();
        $i=  $db->IntDB("create",Model::$sql);
        return $i;
    }

    /**
     * 根据ID查询的方法
     * @param $id
     * @return array
     */
    public static function find($id)
    {
        self::wl_get_class();
//
        $arr= (new self())->setName(self::$name);
         (new self())->where(['id='=>$id]);
        $info= (new self())->get();
        return $info;

    }

    /**执行修改操作
     * @param $arr
     * @return int|string
     */
    public static function update($arr)
    {
        self::wl_get_class();
//
        $name=self::$name;
        $db=new DB();
        $columnArr= $db->columnDB($name);
        $columnString=implode(",",$columnArr);



        $setInfo="";

        foreach ($arr as $key=>$item)
        {

            $setInfo=" $setInfo  $key = '$item' ,";

        }

        $setInfo=substr($setInfo,0,strlen($setInfo)-1);

        $id=$arr["id"];
//        var_dump($arr);
        Model::$sql="UPDATE  $name  SET $setInfo where id = '$id'";
        echo Model::$sql;
        echo "<hr>";

        $i=  $db->IntDB("update",Model::$sql);
        return $i;
    }

    public  static function delete($arr)
    {
        self::wl_get_class();
        $name=self::$name;
        $db=new DB();


        $column="";
        $columnVal="";
        foreach ($arr as $key=>$item) {
            $column=$key;
            $columnVal=$item;
            break;
        }
        Model::$sql="DELETE FROM $name WHERE $column='$columnVal'";
        echo Model::$sql;
        echo "<hr>";

        $i=$db->IntDB("delete",Model::$sql);
        return $i;
    }










}