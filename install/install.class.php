<?php

    //安装模块
    class install{
        //显示协议
        public function index(){
            // echo '安装页面';
            include './index.html';
        }
        //显示系统信息
        public function myserver(){
            if(empty($_POST['yd'])){
                echo '<script>alert("请点击已阅读");location="./index.php?a=index"</script>';exit;
            }
            include './myserver.html';
        }
        //显示让用户输入的数据信息
        public function config(){
            include './config.html';
        }
        //处理用户信息和数据库信息
        public function doconfig(){
            //1.创建数据库
            //1.1连接数据
            $link=mysqli_connect($_POST['host'],$_POST['dbuser'],$_POST['dbpwd']);
            //1.2删除数据库
            mysqli_query($link,'DROP DATABASE IF EXISTS'.$_POST['db']);
            //1.3创建数据库
            mysqli_query($link,'CREATE DATABASE IF NOT EXISTS'.$_POST['db']);
            //1.4选择数据库
            mysqli_select_db($link,$_POST['db']);
            //1.5设置字符串
            mysqli_set_charset($link,'utf8');
            //2.创建数据表 
            include './project1.php';
                //获取sql语句 循环发送
               foreach($arr as $value){
                 mysqli_query($link,$value);
                 echo '创建表成功</br>';
               }
            //3.将管理员用户条件到用户表中
            //3.1获取当前添加时间
            $time=time();
            //3.2将密码加密
             $pwd = md5($_POST['adminpwd']);
            //3.3准备sql
            $sql="INSERT INTO user(name,password,level,status,addtime) VALUES('{$_POST['name']}','{$pwd}',3,0,'{$time}')";
            // 发送sql
            $result=mysqli_query($link,$sql);
            if($result && mysqli_affected_rows($link)>0){
                unlink('./qingge.lock');
                echo '安装成功';
                echo '<a href="../index.php?">前台</a>';
                echo '<a href="../admin/index.php">后台</a>';
            }else{
                echo '安装失败';
            }
            //4.修改config文件
            $str=<<<EOF
<?php
    //主机名
    define('HOST','{$_POST['host']}');
    //用户名
    define('USER','{$_POST['dbuser']}');
    //密码
    define('PWD','{$_POST['dbpwd']}');
    //数据库名
    define('DB','{$_POST['db']}');
    //字符集
    define('CHARSET','utf8');
EOF;
    //替换的方式写入到配置文件
    file_put_contents('../public/Config/config.php',$str);

        }
    }