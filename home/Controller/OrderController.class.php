<?php
    //订单模块
    class OrderController{
        //购物车结账判断
        public function accounts(){
            if(empty($_SESSION['home'])){
                echo '<script>alert("登录才可以结账哦");location="./index.php?c=index&a=login"</script>';        
            }else{
                if(!empty($_SESSION['cart'])){
                    $this->showcart();
                }else{
                    echo '<script>alert("购物车为空哦,快去购物吧");location="./index.php?c=index&a=index"</script>';
                }
            }

        }
        //购物车
        public function showcart(){
            // var_dump($_POST);
        //统计购物车数量
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
                 $sum+=$value['price']*$value['num'];
            }
        }else{
            $sum=0;
        }
        
        $cart = new IndexController;
        $cart->nav();
        include './View/cart/order.html';
        }
        //数量加
        public function jia(){
            $id=$_GET['id'];
            //购买数量增加
            $_SESSION['cart'][$id]['num']=$_SESSION['cart'][$id]['num']+1;
            header('location:index.php?c=order&a=showcart');
        }
        //数量减
        public function jian(){
            $id=$_GET['id'];
            //购买数量增加
            $_SESSION['cart'][$id]['num']=$_SESSION['cart'][$id]['num']-1;
            if($_SESSION['cart'][$id]['num']<1){
                $_SESSION['cart'][$id]['num']=1;
            }
            header('location:index.php?c=order&a=showcart');
        }
        
        /*----------------订单处理-----------------------*/
        //1订单表成功后进入订单详情表
        public function orderadd(){
            //uid值
            $_POST['Address']['uid']=$_SESSION['home']['id'];
            //拿取总金额
            if($_SESSION['cart']){
                foreach($_SESSION['cart'] as $value){
                    $sum+=$value['price']*$value['num'];
                }
            }else{
                $sum=0;
            }       
            //拿取金额总值
            $_POST['Address']['total']=$sum;
            $_POST['Address']['addtime']=time();
            $order=$_POST['Address'];
            // var_dump($order);exit;
            //启动PDO添进数据库
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();
            }
            $pdo->setAttribute(3,1);
            //订单表
            $sql="INSERT INTO orders(linkname,address,tel,code,message,uid,total,addtime) values(:linkname,:address,:tel,:code,:message,:uid,:total,:addtime)";
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute($order);
            //判断是否成功
            if($stmt){
                // echo '下单成功';
                // 获取最后添加的ID值
                $_SESSION['orderid']=$pdo->lastInsertId();
                $this->orderinfo();
            }else{
                echo '<script>alert("当前下单用户较多,请稍后再来");location="./index.php?c=order&a=showcart"</script>';exit;
            } 
        }
        //2订单详情表成功后进入方法
        public function orderinfo(){
            // var_dump($_SESSION['cart']);exit;
            //开启PDO
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();
            }
            $pdo->setAttribute(3,1);
            $sql="INSERT INTO order_info(oid,gid,gname,price,gnum) values(:oid,:gid,:gname,:price,:gnum)";
            $stmt=$pdo->prepare($sql);
            //设置订单ID
            $id=$_SESSION['orderid'];
            $oid=$id;
            foreach($_SESSION['cart'] as $value){
                $stmt->bindParam(':oid',$oid);
                $stmt->bindParam(':gid',$value['id']);
                $stmt->bindParam(':gname',$value['name']);
                $stmt->bindParam(':price',$value['price']);
                $stmt->bindParam(':gnum',$value['num']);
                $stmt->execute();
            }
            //判断是否成功
            if($stmt){
                // echo '下单成功';
                unset($_SESSION['cart']);
                $this->order();
            }else{
                echo '<script>alert("当前下单用户较多,请稍后再来");location="./index.php?c=order&a=showcart"</script>';exit;
            }

        }
        //3.跳转结账
        public function order(){
            header('location:index.php?c=order&a=succeed');
        }
        //4.订单完成
        public function succeed(){
            $top = new IndexController;
            $top->nav(); 
            include './View/cart/succeed.html';
        }
        // 5.付款方法
        public function dopay(){
            var_dump($_POST);
            if(!empty($_POST)){
                $id=$_SESSION['orderid'];
                $map=1;
                // var_dump($id,$map);exit;
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            $sql="UPDATE orders SET status=$map WHERE id=$id";
            // var_dump($sql);exit;
            //发送
            $stmt=$pdo->prepare($sql);
            //执行
            $stmt->execute();
            if(!empty($stmt)){
                echo '<script>
                alert("付款成功");location="./index.php?c=myinfo&a=myorder"
                </script>';
            }else{
                echo '<script>
                alert("请不要重复付款");location="./View/cart/succeed.html";
                </script>';
            } 

            }else{
               echo '<script>
                alert("请选择支付方式");location="./index.php?c=order&a=succeed"
                </script>'; 
            }
        }

        public function __call($a,$b){
            include './View/404.html';
        }
    }