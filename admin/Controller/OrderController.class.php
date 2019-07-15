<?php
    class OrderController{
        public function __construct(){
            if(empty($_SESSION['admin'])){
                header('location:index.php?c=index&a=login');
            }
        }
        //订单模块
        public function index(){
            // var_dump($_SESSION);exit;
            //搜索
            if(empty($_GET['linkname'])){
                $map=array();
            }else{
                $map['linkname']=array('like',$_GET['linkname']);
            }
            // var_dump($map);
            $order = new Model('orders');
            $total = $order->where($map)->count();
            $page = new page($total,5);
            $orderlist = $order->where($map)->limit($page->limit)->select();
            $i=1;
            include './View/Order/index.html';
        }
        // 状态方法
        public function status(){
            $order = new Model('orders');
            $res=$order->find($_GET['id']);
            $data=array();
            $data['id']=$_GET['id'];
            $data['status']=2;
            // var_dump($res['status']);exit;
            // var_Dump($data);exit;
            // 判断是否付款
            if($res['status']==1){
                //如果付款了就进行发货
                if($order->update($data)){
                    echo '<script>alert("宝贝马上出发!");location="./index.php?c=order&a=index"</script>'; 
                }
            }elseif($res['status']==2){
                echo '<script>alert("宝贝已经出发啦");location="./index.php?c=order&a=index"</script>';
            }elseif($res['status']==0){
                echo '<script>alert("用户还未付款");location="./index.php?c=order&a=index"</script>';
            }elseif($res['status']==2){
                echo '<script>alert("用户已经收货了!");location="./index.php?c=order&a=index"</script>';
            }elseif($res['status']==4){
                echo '<script>alert("用户都已经完成订单了!");location="./index.php?c=order&a=index"</script>';
            }
            
        }
        //订单详情
        public function info(){
            $id=$_GET['id']; 
            //使用分页
            $info=new Model('order_info');
            $total=$info->count();
            $page=new page($total,5);
            //启动PDO关联查询
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
            include './View/order/info.html';
        }
        // 编辑订单
        public function edit(){
            // echo '编辑';exit;
            $id=$_GET['id'];
            $order = new Model('orders');
            $res=$order->find($id);
            include './View/order/edit.html';
        }
        //处理编辑订单
        public function doedit(){
            // var_dump($_POST);exit;
            $order =new Model('orders');
            $res=$order->update($_POST);
            if($res){
                echo '<script>alert("修改成功");location="./index.php?c=order&a=index"</script>';
            }else{
                echo '<script>alert("修改失败");location="./index.php?c=order&a=index"</script>';
            }

        }

    }