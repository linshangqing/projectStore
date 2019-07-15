<?php
    //单个商品显示
    class detailsController{
        public function index(){
            $top = new IndexController;
            $top->nav(); 
            //查询商品
            // var_dump($_GET);
            $id=$_GET['id'];
            //查找商品
            // $goods=new Model('goods');
            // $goodlist=$goods->find($id);
            // var_dump($goodlist);
            $dsn = 'mysql:host='.HOST.';dbname='.DB.';charset='.CHARSET;
            try{
                $pdo=new PDO($dsn,USER);
            }catch(PDOexception $e){
                echo $e->getMessage();exit;
            }
            $pdo->setAttribute(3,1);
            //预处理
            $sql='SELECT*FROM goods where id=:id';
            //发送
            $stmt=$pdo->prepare($sql);
            //绑定参数
            $stmt ->bindParam(':id',$id);
            //执行
            $stmt->execute();

            if($stmt->rowCount()){
                //拿出所有内容
                $types=$stmt->fetch(2);
                // var_dump($types);
                //用来遍历
                $pid=$types;
            }else{
                echo '没有内容';
            } 
            include './View/details/details.html';
        }

    }