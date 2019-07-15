<?php
    //分类管理模块
    class TypeController{
        public function __construct(){
            if(empty($_SESSION['admin'])){
                header('location:index.php?c=index&a=login');
            }
        }
        //分类管理页
        public function index(){
            // var_dump($_GET);    
            //没有点击查看子分类
            if(empty($_GET['pid'])){
                $map['pid']=0;
            }else{
                //点击查看子分类
                $map['pid']=$_GET['pid'];
            }
            //遍历数据
            $type = new Model('type');
            $typelist=$type->where($map)->select();
            // var_dump($typelist);
            $i=1;
            include './View/type/index.html';
        }
        //显示方法
        public function display(){
            //修改
            $data = array();
            $data['id']=$_GET['id'];
            $data['display']=$_GET['display'];

            //数据库修改
            $user = new Model('type');
            if($user->update($data)){
                header('location:index.php?c=type&a=index');
            }else{
                header('location:index.php?c=type&a=index');
            }

        }
        //分类添加页
        public function add(){
            //如果你点的是添加分类
            if(empty($_GET['pid'])){

                $pid= 0;
                $path='0, ';
            }else{
                //那么就是点击添加子分类
                $pid=$_GET['pid'];
                //拼接子类path
                $type=new Model('type');
                $typeinfo=$type->find($pid);
                $path= $typeinfo['path'].$pid.',';
                // var_dump($path);
            }
            include './View/Type/add.html';
        }
        //处理添加的方法
        public function doadd(){
            // var_dump($_POST);
            if($_POST['name']==''){
                echo '<script>alert("类别字段不能为空!");location="./index.php?c=type&a=add"</script>';
            }else{
                if(preg_match('/^[a-zA-Z0-9_*]$/',$_POST['name'])){
                    echo '<script>alert("请输入中文字段");location="./index.php?c=user&a=add"</script>';exit;
                }
                $type=new Model('type');
                if($type->add($_POST)){
                    echo '<script>alert("添加成功");location="./index.php?c=type&a=index"</script>';
                }else{
                    echo '<script>alert("添加失败");location="./index.php?c=type&a=add"</script>';
                }
            }
        }
        public function del(){
            // var_dump($_GET);
            //先判断pid是否有值来辨别是父类还是子类
            $type=new Model('type');
            $map['pid']=$_GET['id'];
            // var_dump($map);
            $result=$type->where($map)->select();
            // var_dump($result);
            // 若有值不可删除
            if($result){
                echo '<script>alert("不可以删除此类");location="./index.php?c=type&a=index"</script>';
            }else{
                //说明没有值说明是父类
                if($type->delete($_GET['id'])){
                    header('location:./index.php?c=type&a=index');
                }else{
                header('location:./index.php?c=type&a=index');
                }
            }
        }
        //获取修改内容
        public function edit(){
            $id=$_GET['id'];
            $type = new Model('type');
            $typeinfo=$type->find($id);
            //获取path
            // $pid=$typeinfo['pid'];
            // $path= $typeinfo['path'].$pid.',';
            
            include './View/Type/edit.html';
        }
        //操作修改
        public function doedit(){
            // var_dump($_POST);
            $type = new Model('type');
            $bool = $type->update($_POST);
            if($bool){
                echo '<script>alert("修改成功");location="./index.php?c=type&a=index"</script>';
            }else{
                echo '<script>alert("修改失败");location="./index.php?c=type&a=edit&id=$id"</script>';
            }
        }
        public function __call($a,$b){
            include './View/404.html';
        }
    }