<?php
    class IndexController{
        //导航方法
        public function nav(){
            //使用PDO
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            //预处理
            $sql='SELECT id,name,pid,path,display FROM type WHERE display=0';
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute();

            if($stmt->rowCount()){
                //拿出所有内容
                $types=$stmt->fetchAll(2);
                // var_dump($types);
                //用来遍历
                $pid=$types;
            }else{
                echo '没有内容';
            }
            //购物车商品数量
            if($_SESSION['cart']){
                foreach($_SESSION['cart'] as $value){
                   $nums+=$value['num'];
                }
            }else{
                $nums=0;
            }
            $i=2;
            include './View/head.html';
        }
        //首页商品
        public function index(){
            //引入导航方法
            $this->nav();
            //查询所有商品信息
            $dsn='mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
                $pdo->setAttribute(3,1);
                //判断你是否点击导航
                if(empty($_GET['typeid'])){
                    //若没有点击导航显示所有商品
                    $sql='SELECT*FROM goods WHERE `status`=0 LIMIT 12';
                    $stmt = $pdo->prepare($sql);
                }else{
                    //若点击则显示你点击的导航下的内容
                    $sql='SELECT*FROM goods WHERE `status`=0 and typeid=:typeid LIMIT 12';
                    $stmt = $pdo->prepare($sql);
                    //绑定参数
                    $stmt ->bindParam(':typeid',$_GET['typeid']);
                }
                $stmt->execute();
                if($stmt->rowCount()){
                    $goods=$stmt->fetchAll(2);
                    // var_dump($goods);
                }
                            
            }catch(PDOextepsion $e){
                echo $e->getmessage();
            }
            include './View/middle.html';
            include './View/goods.html';
            //友情链接
            $this->link();            
        }
        // 友情链接模块
        public function link(){
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            //预处理
            $sql='SELECT id,name,url,addtime FROM link';
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute();

            if($stmt->rowCount()){
                //拿出所有内容
                $linklist=$stmt->fetchAll(2);
                // var_dump($linklist);exit;
            }else{
                echo '没有内容';
            }
            include './View/foot.html';
        }
        //注册
        public function register(){

            include './View/register.html';
        }
        // 处理注册
        public function doregister(){
            //判断用户是否恶意注册 
            if($_POST['name']==''||$_POST['password']==''||$_POST['repassword']==''){
                echo '<script>alert("不能有一项内容为空!");location="./index.php?c=index&a=register"</script>';
            }
            //名字验证
            $name=$_POST['name'];
            //检查名字4到16位（字母，数字，下划线）
            if(!preg_match('/^[a-zA-Z0-9_]{4,16}$/',$name)){
                echo '<script>alert("账号不符合规定,请使用数字字母下划线,且长度为4~16位");location="./index.php?c=index&a=register"</script>';exit;
            }
            //1判断密码是否正确
            if($_POST['password']!=$_POST['repassword']){
                echo '<script>alert("密码输入不一致");location="./index.php?c=index&a=register"</script>';exit;
            }
            //2删除多余密码字段
            unset($_POST['repassword']);
            //3开始加密
            $_POST['password']=md5($_POST['password']);
            //4将缺少的数据库字段添加进去
            $_POST['addtime']=time();
            //传递数据
            $res=$_POST;
            // var_dump($res);exit;
            //导入数据库
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            //预处理
            $sql="INSERT INTO user(name,password,addtime) VALUES(:name,:password,:addtime)";
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute($res);
            // var_dump($stmt);exit;
            if(!empty($stmt)){
                echo '<script>
                alert("注册成功");location="./index.php?c=index&a=index"
                </script>';
            }else{
                echo '<script>
                alert("注册失败");location="./index.php?c=index&a=register"
                </script>';
            }
        }
        //前台登录
        public function login(){
            include './View/login.html';
        }
        //登录操作
        public function dologin(){
            // var_dump($_POST);
            //判断用户是否恶意登录 
            if($_POST['name']==''||$_POST['password']==''||$_POST['password']==''){
                echo '<script>alert("账号或密码不能为空!");location="./index.php?c=index&a=login"</script>';
            }
            $password=$_POST['password']=md5($_POST['password']);
            $name=$_POST['name'];
            $map['name']=$name;
            $map['password']=$password;
            // $map['level']=array('gt',0);

            unset($_POST['_x']);
            unset($_POST['_y']);
            unset($_POST['x']);
            unset($_POST['y']);

            $myuser=new Model('user');
            $user=$myuser->where($map)->select();
            if($user){
                //不让密码存储在session
                unset($user[0]['password']);
                //存储账号信息
                $_SESSION['home']=$user[0];

                echo '<script> alert("登录成功");location="./index.php?c=index&a=index"</script>';
            }else{
                echo '<script> alert("账号或密码错误");location="./index.php?c=index&a=login"</script>';
            }

        }
        //退出登录
        public function outlog(){
            //销毁session
            unset($_SESSION['home']);
            //跳转到登录页面
            header('location:index.php');
        }
        public function __call($a,$b){
            include './View/404.html';
        }
        
    }