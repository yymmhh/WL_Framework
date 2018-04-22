<html>
<head>

    <title>123</title>
    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
    <body>

    <ul class="list-group">
    <?php

    require_once "Model/result.php";


     foreach (result::all() as $item){



     ?>

         <li  class="list-group-item"><?php echo $item["StudentNo"]; ?></li>
         <li  class="list-group-item"><?php echo $item["SubjectNo"]; ?></li>

    <?php

     }


    ?>


        <?php
        $info=new result();
        $info->where()->select();
        ?>
    </ul>
    </body>

</html>