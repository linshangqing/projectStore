<?php
    //商品模块
    class GoodsController{
        public function __construct(){
            if(empty($_SESSION['admin'])){
                header('location:index.php?c=index&a=login');
            }
        }
        public function index(){
            //搜索
            if(empty($_GET['name'])){
                $map=array();
            }else{
                $map['name']=array('like',$_GET['name']);
            }
            // var_dump($_GET);exit;
            $goods=new Model('goods');
            $total=$goods->where($map)->count();
            $page = new page($total,5);
            $goodslist = $goods->where($map)->limit($page->limit)->select();
            $i=1;
            include './View/goods/index.html';
        }
        //状态方法
        public function status(){
            //修改
            $data = array();
            $data['id']=$_GET['id'];
            $data['status']=$_GET['status'];

            //数据库修改
            $user = new Model('goods');
            if($user->update($data)){
                header('location:index.php?c=goods&a=index');
            }else{
                header('location:index.php?c=goods&a=index');
            }

        }
        //添加商品
        public function add(){
            $type=new Model('type');
            $result=$type->order('concat(path,id,",") ASC')->select();
            // var_Dump($result);
            include './View/Goods/add.html';
        }
        //处理添加商品
        public function doadd(){
            // var_Dump($_POST);exit;
            $_POST['pic']='';
            
            // var_dump($_POST);exit;
            //处理文件上传
            $upload = new Uploads('pic');
            //允许上传的类型
            $upload ->typelist = array('image/jpeg','image/jpg','image/gif','image/png');
            //保存的路径
            $upload->path='../public/goods/';
            //进行上传
            $bool =$upload->upload();
            if(empty($bool)){
                echo '<script>alert("文件未上传");location="./index.php?c=goods&a=add"</script>';exit;
            }
            //规范上传成功的文件名
            $_POST['pic']=$upload->savename;
            //需要排除没有填写的内容
            foreach($_POST as $value){
                if($value ==''){
                    echo '<script>alert("不可以有任何一项信息为空!");location="./index.php?c=goods&a=add"</script>';exit;
                }
            }
            // var_dump($_POST);exit;
            $goods = new Model('goods');
            if($goods->add($_POST)){
                echo '<script>alert("添加商品成功");location="./index.php?c=goods&a=add"</script>';exit;
            }else{
                unlink('../public/goods/'.$_POST['pic']);
                echo '<script>alert("添加商品失败");location="./index.php?c=goods&a=add"</script>';exit;
            }
        }
        //删除模块
        public function del(){
            $gid = $_GET['id'];
            // var_dump($gid);
            $goods = new Model('goods');
            $goodslist = $goods->find($gid);
            $img =$goodslist['pic'];
            $result = $goods->delete($gid);
            if($result){
                unlink('../public/goods/'.$img);
                header('location:index.php?c=goods&a=index');
            }else{
                header('location:indexp.php?c=goods&a=index');
            }

        }
        //编辑
        public function edit(){
            //找到商品数据
            $id=$_GET['id'];
            $goods = new Model('goods');
            $res=$goods->find($id);
            // var_dump($res);
            //打开分类
            $type= new Model('type');
            $result=$type->order('concat(path,id,",") ASC')->select();

            include './View/goods/edit.html';
        }
        //操作编辑
        public function doedit(){

        $id = $_POST['id'];
        // var_dump($id);exit;
        $goods = new Model('goods');
        //获取数据
        $row = $goods->find($id);
        // 假设没有新图片上传，只进行其他操作
        if($_FILES['pic']['name']==''){

            if($goods->update($_POST)){
                echo '<script>alert("修改成功");location="./index.php?c=goods&a=index"</script>';
            }else{
                //上传失败 删除上传的文件
                unlink('../public/goods/'.$_POST['pic']);
                echo '<script>alert("修改失败");location="./index.php?c=goods&a=index"</script>';
            }
        }else{
            // 如果有新图片上传，进行上传操作
            // 得到文件上传类对象
            $upload =new Uploads('pic');
            // 允许上传的类型
            $upload->typelist =array('image/jpeg','image/jpg','image/gif','image/png');
            // 初始化保存路径
            $upload->path = '../public/goods/';
            // 进行上传
            $bool = $upload->upload();
            if(!$bool){
                echo '<script>alert("文件上传失败");location="./index.php?c=goods&a=index"</script>';
                exit;
            }
            // 拿取旧图片名
            $oldname = $_POST['pic'];

            // 新图片名替换旧图片名
            $_POST['pic']=$upload->savename;

            if($goods->update($_POST)){
                // 删除旧图片
                unlink('../public/goods/'.$oldname);
                echo '<script>alert("修改成功");location="./index.php?c=goods&a=index"</script>';
            }else{
                //上传失败 删除上传的文件
                unlink('../public/goods/'.$_POST['pic']);
                echo '<script>alert("修改失败");location="./index.php?c=goods&a=index"</script>';
            }
        }
        }
        public function __call($a,$b){
            include './View/404.html';
        }
    }