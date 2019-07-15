<?php
    //个人中心模块
    class MyinfoController{
        public function __construct(){
            if(empty($_SESSION['home'])){
                header('location:index.php?c=index&a=login');
            }
        }
        // 头部方法
        public function top(){
            //购物车商品数量
            if($_SESSION['cart']){
                foreach($_SESSION['cart'] as $value){
                   $nums+=$value['num'];
                }
            }else{
                $nums=0;
            }
            include './Include/top.html';
        }
        //我的中心页面
        public function index(){
            
            if(empty($_SESSION['home'])){
                echo '<script>alert("尊敬的朋友,请先登录");location="./index.php?c=index&a=login"</script>';
            }else{

                $this->top();
                include './View/center/index.html';
            }
        }
        //订单管理页面
        public function myorder(){
            $this->top();
            
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            //预处理
            $sql='SELECT id,linkname,address,tel,code,addtime,total,status FROM orders';
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute();

            if($stmt->rowCount()){
                //拿出所有内容
                $orders=$stmt->fetchAll(2);
                // var_dump($orders);exit;
            }else{
                echo '没有内容';
            }

            include './View/center/order.html';
        }
        // 订单管理页面--订单详情
        public function info(){
            $this->top();
            $id=$_GET['id'];
            // 启动PDO关联查询
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();
            }
            $pdo->setAttribute(3,1);
            //关联图片遍历查询
            $sql="SELECT o.id,o.oid,o.gid,o.gname ,o.price,o.gnum,g.pic FROM order_info o ,goods g WHERE o.oid=$id and o.gid=g.id and g.pic";
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute();
            if($stmt->rowCount()){
                //拿出所有内容
                $orderinfo=$stmt->fetchAll(2);
                // var_dump($orderinfo);exit;
            }

            $i=1;
            include './View/center/orderinfo.html';
        }
        // 付款方法
        public function pay(){
            $id=$_GET['id'];
            if($_GET['status']==0){
            // var_dump($id,$map);exit;
                $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
                try{
                    $pdo=new PDO($dsn,USER);
                }catch(PDOexception $e){
                    echo $e->getMessage();exit;
                }
                $pdo->setAttribute(3,1);
                $sql="UPDATE orders SET status=1 WHERE id=$id";
                //发送
                $stmt=$pdo->prepare($sql);
                //执行
                $stmt->execute();
                if(!empty($stmt)){
                    echo '<script>
                    alert("付款成功");location="./index.php?c=myinfo&a=myorder"
                    </script>';
                }
            }else{
                echo '<script>
                    alert("您已经付过款了");location="./index.php?c=myinfo&a=myorder"
                    </script>';
            }

        }
        // 删除订单
        public function del(){
            $id=$_GET['id'];
            if($_GET['status']==0||$_GET['status']==3||$_GET['status']==4){
                echo '删除';
                $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
                try{
                    $pdo=new PDO($dsn,USER);
                }catch(PDOexception $e){
                    echo $e->getMessage();
                }
                $pdo->setAttribute(3,1);
                $sql="DELETE FROM orders WHERE id=$id";
                $stmt=$pdo->prepare($sql);
                $stmt->execute();
                $sql="DELETE FROM order_info WHERE oid=$id";
                $stmt=$pdo->prepare($sql);
                $stmt->execute();
                if($stmt){
                    echo '<script>alert("删除成功");location="./index.php?c=myinfo&a=myorder"</script>';
                }
            }else{
                echo '<script>alert("亲~未付款或者订单完成才能删除哦");location="./index.php?c=myinfo&a=myorder"</script>';
            }
        }
        //消息管理页面
        public function message(){
            $this->top();
            include './View/center/message.html';
        }

    }