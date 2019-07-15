<?php
    //安装页面
    echo '安装页面';
    //安装类引入
    include './install.class.php';
    //得到对象
    $install= new install;

    //获取方法名
    $a =isset($_GET['a'])?$_GET['a']:'index';
    //调用方法
    $install->$a();