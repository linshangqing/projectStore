<?php
    //判断是否安装 判断qingge.lock
    if(file_exists('./install/qingge.lock')){
        //存在的话说明你没有安装过
        header('location:./install/index.php'); 
    }else{
        //没有了说明安装成功
        header('location:./home/index.php');
    }