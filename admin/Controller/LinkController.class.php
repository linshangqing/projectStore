<?php
    //链接模块
    class LinkController{
        public function index(){
            if(empty($_GET['name'])){
                $map=array();
            }else{
                $map['name']=array('like',$_GET['name']);
            }
            $link= new Model('link');
            $total=$link->where($map)->count();
            $page=new Page($total,8);
            $linklist=$link->select();


            $i=1;
            include './View/link/index.html';
        }
        // 添加链接
        public function add(){
            include './View/link/add.html';
        }
        // 添加处理
        public function doadd(){
            if($_POST['name']==''||$_POST['url']==''){
                echo '<script>alert("请勿非法增添空字段!");location="./index.php?c=link&a=add"</script>';
            }else{
                $_POST['addtime']=time();
                // var_dump($_POST);
                $link = new Model('link');
                // exit;
                $bool = $link->add($_POST);
                if($bool){
                    echo '<script>alert("添加成功");location="./index.php?c=link&a=index"</script>';
                }else{
                    echo '<script>alert("添加失败");location="./index.php?c=link&a=add"</script>';
                }
            }

        }
        //状态方法
        public function display(){
            //取值
            $data=array();
            $data['id']=$_GET['id'];
            $data['status']=$_GET['status'];
            //修改
            $link= new Model('link');
            if($link->update($data)){
                header('location:index.php?c=link&a=index');
            }else{
                header('location:index.php?c=link&a=index.');
            }
            
        }
        //编辑
        public function edit(){
            $id=$_GET['id'];
            $link= new Model('link');
            $linklist= $link->find($id);
            include './View/link/edit.html';
        }
        //处理编辑
        public function doedit(){
            $_POST['addtime']=time();
            $link=new Model('link');
            $bool=$link->update($_POST);
            if($bool){
                echo '<script>alert("修改成功");location="index.php?c=link&a=index"</script>';
            }else{
                echo '<script>alert("修改失败");location="index.php?c=link&a=index"</script>';
            }

        }

    }