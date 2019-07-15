<?php
    include '../public/Config/config.php';
    // include './Model/Model.class.php';
    // include './org/Page.class.php';
    // include './controller/UserController.class.php';
    // include './controller/GoodsController.class.php';
    // include './controller/IndexController.class.php';
    
    //开启session
    session_start();

    //错误屏幕
    error_reporting(E_ALL ^ E_NOTICE);

    //时区
    date_default_timezone_set('PRC');

    // function __autoload($className){
        spl_autoload_register('auto');
        function auto($className){
        //echo $className;
        //判断你要加载的是什么类型文件
        if(substr($className,-10)=='Controller'){
            include  './Controller/'.$className.'.class.php';
        }elseif(substr($className,-5)=='Model'){
            include './Model/'.$className.'.class.php';
        }else{
            include './Org/'.$className.'.class.php';
        }
        
    }
    //如果想要使用用户相关类.
    //需要做如下操作
    
    //为了让各种情况兼容
    //1.先使用strtolower()函数将所有参数统一变为小写
    //2.再使用ucfirst()首字母大写 
    //传递要new哪个类
    
    //传递类
    $c = isset($_GET['c'])?$_GET['c']:'Index';

    $c = ucfirst(strtolower($c));
    $controller = $c.'Controller';
    $info = new $controller;    

    //传递方法名
    $a = isset($_GET['a'])?$_GET['a']:'Index';
    $info->$a();
    // var_dump($info);


    //这个方法很重要
    //有如下地址:index.php?c=goods&a=index
    //goods:表示要跳转的类
    //index:表示要跳转类的哪个方法
