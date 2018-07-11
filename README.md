# WL_Framework

<h2>自己手写的一个仿造Laravel和ThinkPHP的 MC 框架没有V😂</h2>
  

2018-7-4 加入命名空间.

访问 第一个是     控制器/方法


控制器定义在app目录下

            index.php?c=index/find


2018-7-8 加入URL优化.

/模块/控制器/方法

    /admin/index/index
	
	
	
2018-7-1 各种SQL优化


查询

    $sql = subject::all()
              ->where(['SubjectNo>'=>"1",'gradeid='=>'1'])             
              ->where(['ClassHour!='=>'36'])
              ->order('SubjectNo','desc')
              ->limt('3','1')
              ->get();
             
             
       查询所有
             $zans= Zan::all()->get();
     
             dd($zans);
             
       单个查询
             
             $zans= Zan::find(1);
             
             dd($zans);         
              
增加

        $zans= Zan::create(['id'=>130]);

        dd($zans);              
 
 修改
 
         $zans= Zan::update(['id'=>130]);
 
         dd($zans);
 
 
 删除
 
            $zans= Zan::delete(['id'=>130]);
    
            dd($zans);
            
            
一对多

            $post = User::all()->HasMany(User_Info::class, ['id', 'user_id'])->get();
            数组第一个是本模型的主键第二个是关联的模型的外键
            
            
一对一

            $post = User::all()->HasOne(User_Info::class, ['id', 'user_id'])->get();
            数组第一个是本模型的主键第二个是关联的模型的外键
            
