<?php
    class IndexController{
        public function Index(){
            // echo '后台首页';
            if(empty($_SESSION['admin'])){
                header('location:index.php?c=index&a=login');
            }else{
                include './View/index.html';
            }
        }
        //登录页面
        public function  login(){
            include './View/login.html';
        }
        //登录操作
        public function dologin(){
            // var_dump($_POST);
            // exit;
            //判断用户是否符合规则
            $password = $_POST['password']=md5($_POST['password']);
            $name=$_POST['name'];
            $map['name']=$name;
            $map['password']=$password;
            //判断是否是管理员
            $map['level']=array('gt',1);
            // 判断是否被禁用
            $map['status']=0;
            // var_dump($map);
            //删除多余的
            unset($_POST['_x']);
            unset($_POST['_y']);
            unset($_POST['x']);
            unset($_POST['y']);

            // var_dump($_POST);
            $myuser= new Model('user');

            $user=$myuser->where($map)->select(); 

            // var_dump($user);
            // exit;
            if($user){
                //不让密码存储在session
                unset($user[0]['password']);
                //存储账号信息
                $_SESSION['admin']=$user[0];

                echo '<script> alert("登录成功");location="./index.php?c=index&a=index"</script>';
            }else{
                echo '<script> alert("账号或密码错误");location="./index.php?c=index&a=login"</script>';
            }
            
        }
        
        //退出登录
        public function outlog(){
            //销毁session
            unset($_SESSION['admin']);
            //跳转到登录页面
            header('location:index.php');
        }
        public function __call($a,$b){
            include './View/404.html';
        }
    }