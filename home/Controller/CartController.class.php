<?php
    //购物车模块
    class CartController{
        public function addCart(){
            if(!empty($_GET['id'])){
                if(!empty($_SESSION['cart'][$_GET['id']])){
                    $_SESSION['cart'][$_GET['id']]['num']+=1;
                    // var_dump($_SESSION);exit;
                    echo '<script>alert("加入购物车成功");location="./index.php?c=index&a=index"</script>';exit;
                }

                //开启PDO 指定商品添加到购物车
                try{
                    $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
                    $pdo=new PDO($dsn,USER);
                    $pdo->setAttribute(3,1);
                    $sql="SELECT * FROM goods WHERE id=:id";
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':id',$_GET['id']);
                    //执行sql语句
                    $stmt->execute();
                    if($stmt->rowCount()){
                        //拿取数据
                        $row=$stmt->fetch(2);
                        //添加一个购物数量
                        $row['num']=1;
                        //添加到购物车
                        $_SESSION['cart'][$_GET['id']]=$row;
                        echo '<script>alert("加入购物车成功");location="./index.php?c=index&a=index"</script>';
                    }
                }catch(PDOexception $e){
                    echo $e->getMessage();
                }
            }else{
                // 没有指定商品
                echo '<script>alert("请添加指定商品");location="./index.php?c=index&a=index"</script>';
            }
        }
        //购物车
        public function index(){
            //将导航引入
            $index = new IndexController;
            $index->nav();
            $i=1;
            $total=0;
            // var_dump($_SESSION['cart']);
            //购物车商品数量
            if($_SESSION['cart']){
                foreach($_SESSION['cart'] as $value){
                     $nums+=$value['num'];
                }
            }else{
                $nums=0;
            }
            //购物车总金额
            if($_SESSION['cart']){
                foreach($_SESSION['cart'] as $value){
                    echo $sum+=$value['price']*$value['num'];
                }
            }else{
                $sum=0;
            }
            include './View/Cart/showCart.html';
        }
        //数量加
        public function jia(){
            $id=$_GET['id'];
            //购买数量增加
            $_SESSION['cart'][$id]['num']=$_SESSION['cart'][$id]['num']+1;
            header('location:index.php?c=cart&a=index');
        }
        //数量减
        public function jian(){
            $id=$_GET['id'];
            //购买数量增加
            $_SESSION['cart'][$id]['num']=$_SESSION['cart'][$id]['num']-1;
            if($_SESSION['cart'][$id]['num']<1){
                $_SESSION['cart'][$id]['num']=1;
            }
            header('location:index.php?c=cart&a=index');
        }
        //删除某个商品
        public function del(){
            $id=$_GET['id'];
            unset($_SESSION['cart'][$id]);
            header('location:index.php?c=cart&a=index');
        }
        //清空购物车
        public function delete(){
            unset($_SESSION['cart']);
            header('location:index.php?c=cart&a=index');
        }
        public function __call($a,$b){
            include './View/404.html';
        }
    }