<?php
    //用户管理模块
    class UserController{
        public function __construct(){
            if(empty($_SESSION['admin'])){
                header('location:index.php?c=index&a=login');
            }
        }
        //主页
        public function index(){
            // var_dump($_GET);
            if(empty($_GET['name'])){
                $map=array();
            }else{
                $map['name']=array('like',$_GET['name']);
            }
            $user=new Model('user');
            $total=$user->where($map)->count();
            $page = new page($total,5);
            $userlist=$user->where($map)->limit($page->limit)->Select();
            // var_dump($userlist);
            $i=1;
            include './View/User/index.html';

        }
        //添加方法
        public function add(){
            // echo '添加用户';
            $info= new Model('user');
            include './View/User/add.html';
        }
        //处理添加
        public function doadd(){
            // var_dump($_POST);exit;
            
            //判断用户是否恶意添加 
            if($_POST['name']==''||$_POST['password']==''||$_POST['repassword']==''){
                echo '<script>alert("不能有一项内容为空!");location="./index.php?c=user&a=add"</script>';
            }
            //名字验证
            $name=$_POST['name'];
            //检查名字4到16位（字母，数字，下划线）
            if(!preg_match('/^[a-zA-Z0-9_]{4,16}$/',$name)){
                echo '<script>alert("账号不符合规则");location="./index.php?c=user&a=add"</script>';exit;
            }
            //1判断密码是否正确
            if($_POST['password']!=$_POST['repassword']){
                echo '<script>alert("密码输入不一致");location="./index.php?c=user&a=add"  </script>';exit;
            }

            //2删除多余密码字段
            unset($_POST['repassword']);
            //3开始加密
            $_POST['password']=md5($_POST['password']);
            //4将缺少的数据库字段添加进去
            $_POST['status']=0;
            $_POST['addtime']=time();
            // var_dump($_POST);
            //5.将post数组中的值添加到数据库
            $user= new Model('user');
            $bool = $user->add($_POST);
            if(!empty($bool)){
                echo '<script>
                alert("添加成功");location="./index.php?c=user&a=index"
                </script>';
            }else{
                echo '<script>
                alert("添加失败");location="./index.php?c=user&a=index"
                </script>';
            }
        }
        //删除方法
        public function del(){
            $id=$_GET['id'];
            $user=new Model('user');
            if($user->delete($id)){
                header('location:index.php?c=user&a=index');
            }else{
                header('location:index.php?c=user&a=index');
            } 
        }
        //状态方法
        public function status(){
            //修改
            $data = array();
            $data['id']=$_GET['id'];
            $data['status']=$_GET['status'];

            //数据库修改
            $user = new Model('user');
            if($user->update($data)){
                header('location:index.php?c=user&a=index');
            }else{
                header('location:index.php?c=user&a=index');
            }

        }
        //修改用户
        public function edit(){
            $id=$_GET['id'];
            $user=new Model('user');
            $list=$user->find($id);

            include './View/User/edit.html';

        }
        //修改操作
        public function doedit(){
            // var_dump($_POST);exit;
            //1先判断密码是否正确
            if($_POST['repassword']!=$_POST['repassword1']){
                echo '<script>alert("输入密码不一致,请重新输入");location="./index.php?c=user&a=index"</script>';exit;
            }
            $_POST['password']=$_POST['repassword'];
            //删除多余密码字段
            unset($_POST['repassword']);
            unset($_POST['repassword1']);
            //开始加密
            $_POST['password']=md5($_POST['password']);
            //将缺少的数据库字段添加进去
            $_POST['status']=0;
            $_POST['addtime']=time();
            // var_dump($_POST);exit;
            $user= new Model('user');
            $bool=$user->update($_POST);
            if($bool){
            echo '<script>alert("修改成功");location="./index.php?c=user&a=index"</script>';
            }else{
                echo '<script>alert("修改失败");location="./index.php?c=user&a=index"</script>';
            }
        }
        //用户详情
        public function info(){
            $uid=$_GET['id'];
            // var_dump($uid);
            $info= new Model('user_info');
            $map['uid']=$uid;
            // var_dump($map);
            $result=$info->where($map)->find();
            echo $info->sql;
            // var_dump($result);
            include './View/User/user_info.html';
        }
        //处理用户详情
        public function doinfo(){
            // var_dump($_POST);
            //查看数据中有没有用uid 如果有说明你要操作的是修改才做 如果没有则是添加操作
            if($_POST['uid']==''){
                $this->doinfoadd();
            }else{
                $this->doinfoedit();
            }
        }
        protected function doinfoedit(){
             $info = new Model('user_info');
            //判断有无图片编辑
            if($_FILES['pic']['name']==''){
                //无图片改动时
                $map['uid']=$_POST['uid'];
                $result= $info->where($map)->update($_POST);
                if($result){
                    echo '<script>alert("修改成功");location="./index.php?c=user&a=index"</script>';exit;
                }else{
                    echo '<script>alert("修改失败");location="./index.php?c=user&a=index"</script>';exit;
                }
            }else{
                //图片改动时
                //拿取旧图片
                $map['uid']=$_POST['uid'];
                $result = $info->where($map)->find();
                $oldname= $result['pic'];
                //更新图片
                $upload= new Uploads('pic');
                //允许的类型
                $upload->typelist=array('image/jpeg','image/jpg','image/png','image/gif');
                //保存路径
                $upload->path='../public/uploads/';
                //执行文件上传内容
                $bool = $upload->upload();
                if(!$bool){
                    echo '<script>alert("文件上传失败");location="./index.php?c=user&a=index"</script>';exit;
                }
                //文件上传成功
                $newname = $upload->savename;
                //将新图片放入
                $_POST['pic']=$newname;
                $map['uid']=$_POST['uid'];
                $res=$info->where($map)->update($_POST);
                if($res){
                    unlink('../public/uploads/'.$oldname);
                    echo '<script>alert("修改成功");location="./index.php?c=user&a=index"</script>';
                    exit;
                }else{
                    echo '<script>alert("修改失败");location="./index.php?c=user&a=index"</script>';exit;
                }

            }
        }
        //用户详情页的添加
        protected function doinfoadd(){
            // var_dump($_POST);
                //开始上传
            $upload = new Uploads('pic');
                //允许上传的类型
            $upload->typelist=array('image/jpeg','image/gif','image/png');
                //允许保存的路径
            $upload->path='../public/uploads/';
                //开始上传
            $bool=$upload->upload();
            if(!$bool){
                echo '<script>alert("文件上传失败");location="./index.php?c=user&a=index"</script>';exit;
            }
            //上传成功后的图片名称
            $_POST['pic']=$upload->savename;
            $_POST['uid']=$_GET['uid'];
            //添加操作
            $info= new Model('user_info');
            $result=$info->add($_POST);
            if($result){
                echo '<script>alert("添加成功");location="./index.php?c=user&a=index"</script>';exit;
            }else{
                echo '<script>alert("添加失败");location="./index.php?c=user&a=index"</script>';exit;
            }
        }
        //没有方法的时候调用
        public function __call($a,$b){
            include './View/404.html';
        }
    }