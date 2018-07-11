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
    public static $sql = "";
    public static $namespace = "";
    public static $has_name = "";
    public static $where = "";
    public static $has_name_arr = "";


    /**
     * 为sql的表给名字
     */
    public function setName($name)
    {

        self::$name = $name;
        self::$sql = "select * from  $name where 1=1";


        return $this;
    }

    public function HasOne($model, $where)
    {

        $name = self::$name;


        $mode_Index = strrpos($model, '\\');
        $has_name = substr($model, $mode_Index + 1);
        self::$has_name = $has_name;

        $my_parameter = $this->select_parameter($name);
        $has_parameter = $this->select_parameter($has_name);

        self::$sql = "select {$my_parameter} , {$has_parameter} from {$name} left  join {$has_name} on {$name}.{$where[0]} = {$has_name}.{$where[1]} where 1=1 ";

        return $this;
    }


    public function HasMany($arr)
    {

        $my_parameter = $this->select_parameter(self::$name);
        $temp_sql="select {$my_parameter} ";

        $has_name_arr=""; //拿到所有的关联的模型
        $where_arr="";
        foreach($arr as $item){
            $mode_Index = strrpos($item[0], '\\');
            $has_name = substr($item[0], $mode_Index + 1);
            $has_name=strtolower($has_name); //转小写
            $has_name_arr[]=$has_name;
            $item[1]["name"]=$has_name;
            $where_arr[]=$item[1];

        }
        self::$has_name_arr=$has_name_arr;
        $has_parameter_arr="";      //所有关联的sql查询参数
        foreach($has_name_arr as $item){

            $has_parameter= $this->select_parameter($item);
            $has_parameter_arr[]=$has_parameter;
            $temp_sql=$temp_sql . "," .$has_parameter;

        }
        $name=self::$name;
        $temp_sql=$temp_sql . " from {$name} ";
        //开始加入where 的sql语句
        foreach ($where_arr as $item) {
            $temp_sql=$temp_sql . "left  join {$item['name']} on {$name}.{$item[0]} = {$item['name']}.{$item[1]}  ";

        }

        $temp_sql=$temp_sql . " where 1=1 ";

        self::$sql=$temp_sql;

        self::$has_name = $has_name_arr[0];

        return $this;
    }



    //得到类,为了到DB里面new 然后生成
   static function wl_get_class()
    {
        $namespace = get_called_class();

        $mode_Index = strrpos($namespace, '\\');
        $name = substr($namespace, $mode_Index + 1);

        self::$namespace = $namespace;
        self::$name = strtolower($name);
//        $has_name=strtolower($has_name);


        if (!class_exists($namespace)){
            error("调用的控制器不存在");
        };

        //判断是否存在从新定义类名
        if (property_exists($namespace,"table_name"))
        {
            $db_name=new $namespace();
            self::$name=$db_name->table_name;
        };



    }

    /**
     * Where条件
     */


    public function where($sql)
    {

        $tempConnectionSQl = "";

        foreach ($sql as $key => $item) {

            $tempConnectionSQl = $tempConnectionSQl . ' and ' . $key . "'$item'";
        }


        self::$sql = self::$sql . $tempConnectionSQl;

        self::$where=$tempConnectionSQl;

        return $this;
    }

//得到sql 查询的列
    public function select_parameter($name)
    {
        $db = new DB();

        $column_arr = $db->columnDB($name);

        $select_can = "";
        foreach ($column_arr as $item) {
            $select_can = $select_can . " {$name}.{$item}   as '{$name}&{$item} '  , ";
        }
        $select_parameter = substr($select_can, 0, strlen($select_can) - 2);

        return $select_parameter;
    }


    /**
     * 这个式最后的查询数据
     */
    public function get()
    {
//            dd(self::$sql);

        $db = new DB();
        if (self::$has_name == "") {
            return $db->findDB(self::$name, self::$sql, self::$namespace);
        } else {

            return $db->findDB_has_many(self::$name, self::$sql, self::$namespace, self::$has_name_arr,self::$where);
        }


    }


    /**
     * count 查询
     */
    public function count(){
        $db = new DB();
        self::$sql= str_replace("*","count(*) as count",self::$sql);

        return $db->findCountDB(self::$name, self::$sql, self::$namespace);

    }



    /**
     * 第一步的操作
     */
    public static function all()
    {

        self::wl_get_class();

        self::$name=strtolower(self::$name);
        $arr = (new self())->setName(self::$name);
        return new Model();
    }


    /**
     * @param $column 想要查询的列
     * @param string $order 查询的方法 desc 默认式升序
     * @return $this 排序
     */
    public function order($column, $order = "")
    {
        $tempConnectionSQl = self::$sql;


        $tempConnectionSQl = $tempConnectionSQl . " ORDER BY $column $order";
        self::$sql = $tempConnectionSQl;
        return $this;
    }


    /**
     * 分页
     * @param $pageIndex 第几页
     * @param $pageSize 每页几个
     * @return $this
     */
    public function limt($pageIndex, $pageSize)
    {
        $pageIndex = $pageSize * ($pageIndex - 1);
        $tempConnectionSQl = self::$sql;


        $tempConnectionSQl = $tempConnectionSQl . " LIMIT $pageIndex , $pageSize";
        self::$sql = $tempConnectionSQl;

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
        $name = self::$name;

//        $arr= (new self())->setName(self::$name);
        $db = new DB();
        $columnArr = $db->columnDB($name);
        $columnString = implode(",", $columnArr);


        $columnKeys = "";
        $columnVlaues = "";
        foreach ($arr as $key => $item) {
            $columnKeys = $columnKeys . $key . ",";
            $columnVlaues = "$columnVlaues '$item'  ,";

        }
        $columnKeys = substr($columnKeys, 0, strlen($columnKeys) - 1);
        $columnVlaues = substr($columnVlaues, 0, strlen($columnVlaues) - 1);

        self::$sql = "insert into $name ($columnKeys) VALUES ($columnVlaues)";
//        echo self::$sql;
//        echo "<hr>";
//        die();
        $i = $db->IntDB("create", self::$sql);
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
        $arr = (new self())->setName(self::$name);
        (new self())->where(['id=' => $id]);
        $info = (new self())->get();
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
        $name = self::$name;
        $db = new DB();
        $columnArr = $db->columnDB($name);
        $columnString = implode(",", $columnArr);


        $setInfo = "";

        foreach ($arr as $key => $item) {

            $setInfo = " $setInfo  $key = '$item' ,";

        }

        $setInfo = substr($setInfo, 0, strlen($setInfo) - 1);

        $id = $arr["id"];
//        var_dump($arr);
        self::$sql = "UPDATE  $name  SET $setInfo where id = '$id'";
//        echo Model::$sql;
//        echo "<hr>";

        $i = $db->IntDB("update", self::$sql);
        return $i;
    }

    public static function delete($arr)
    {
        self::wl_get_class();
        $name = self::$name;
        $db = new DB();


        $column = "";
        $columnVal = "";
        foreach ($arr as $key => $item) {
            $column = $key;
            $columnVal = $item;
            break;
        }
        self::$sql = "DELETE FROM $name WHERE $column='$columnVal'";
//        echo self::$sql;
//        echo "<hr>";

        $i = $db->IntDB("delete", self::$sql);
        return $i;
    }


}