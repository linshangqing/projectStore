<?php
    class Model{
        public $link;//连接数据库对象
        public $tabName;//存储数据表
        public $limit; //每页显示条数
        public $order;//排序
        public $fields='*';//用来存储要查询的字段
        public $allFields;//用来存储缓存数据库字段
        public $where;
        //搜索条件存储
        public $sql;
        //排错sql语句内容
        
        // 构造
        public function __construct($tabname){
            $this-> getconnect();
            // 初始化数据表
            $this->tabName=$tabname;
        }
        // 增
        public function add($data=array()){
            // 拿键和值
            // var_dump($data);
            $key=array_keys($data);
            //分割数组
            $keys=implode("`,`",$key);
            var_dump($keys);
            $value=array_values($data);
            $values=implode("','",$value);
            var_dump($value);

            $sql ="INSERT INTO {$this->tabName}(`{$keys}`) VALUES('{$values}')";
            return $this->execute($sql);
        }
        // 删
        public function delete($id=''){
            if(empty($id)){
                $where = $this->where;
            }else{
                $where = "WHERE id={$id}";
            }
            if(empty($where)){
                echo '给我一个条件';exit;
            }
            $sql="DELETE FROM {$this->tabName} {$where}";
            return $this->execute($sql);
        }
        // 改
        public function update($data=array()){
            if(!is_array($data)){
                return false;
            }
            if(empty($data)){
                return false;
            }
            //判断你是否使用id作为修改条件如果使用则id有值 如果不是 则使用where条件修改内容
            if(empty($data['id'])){
                $where = $this->where;
            }else{
                //用id作为修改条件
                $where='WHERE id='.$data['id'];
            }
            if(empty($where)){
                return false;
            }
            //将我们传递过来的数组让他的键和值拼接在一起
            $result='';
            foreach($data as $key=>$value){
                if($key !='id'){
                    $result .="`{$key}`='{$value}',";
                }
            }
            //将多出来的逗号去掉
            $result = rtrim($result,',');
            $sql="UPDATE {$this->tabName} SET {$result} {$where}";
            return $this->execute($sql);
        }
        // 查
        public function select(){
            $sql ="SELECT {$this->fields} FROM {$this->tabName} {$this->where} {$this->order} {$this->limit} ";
            echo $sql;
            $userlist=$this->query($sql);
            // var_dump($userlist);
            return $userlist;
        }
        //查询一条数据
        public function find($id=''){
            if(empty($id)){
                $where=$this->where;
            }else{
                $where="where id={$id}";
            }
            $sql="SELECT {$this->fields} FROM {$this->tabName} WHERE id={$id}";
            $userlist=$this->query($sql);
            //将二维数组变为1维
            return $userlist[0];
        }
        // 统计条数
        public function count(){
            $sql = "SELECT count(*) as total FROM {$this->tabName} ";
            $total=$this->query($sql);
            //不要数组
            return $total[0]['total'];
        }
        //每页显示多少条
        public function limit($limit){
            // 注意limit后面空格
            $this->limit=' LIMIT '.$limit;
            // var_dump($this->limit);
            return $this;
        }
        //order by 排序 asc|desc
        public function order($order){
            $this->order='order by '.$order;
            return $this;
        }
        //字段筛选 
        public function field($field=array()){
            // var_dump($filed);exit;
            //判断$field是不是数组
            if(!is_array($field)){
                //保证连贯操作
                return $this;
            }
            //不能是非空的数组
            if(empty($field)){
                return $this;
            }
            // 检测数据库内容 删除数据库没有的字段
            $field = $this->check($field);

            if(empty($field)){
                return $this;
            }
            //拼接字符串得到想要的内容
            $this->fields=join(',',$field);
            // echo $this->fields;exit;

            return $this;
        }
        //where条件
        public function where($data){
            // var_dump($data);
            //判断data是否是数组而且这个数组不能为空
            if(is_array($data) && !empty($data)){
                foreach($data as $key=>$value){
                    if(is_array($value)){
                        switch($value[0]){
                            case 'gt':
                                $result[]="`{$key}`>'{$value[1]}'";
                                break;
                            case 'lt':
                                $result[]="`{$key}`<'{$value[1]}'";
                                break;
                            case 'like':
                                $result[]="`{$key}` like '%{$value[1]}%'";
                               break;
                            case 'in':
                                $result[]="`{$key}` in({$value[1]})";
                                break;
                        }
                    }else{
                        //简单的等于关系
                        $result[]='`'.$key."`='".$value."'";
                    }
                }
                $where ='WHERE'.join('and',$result);
                // echo $where;
                $this->where=$where;
            }else{
                return $this;
            }
            return $this;
        } 
        /*
        ****************辅助方法*****************************
         */
        public function getconnect(){
            $this->link =mysqli_connect(HOST,USER);
            if(mysqli_connect_errno($this->link)>0){
                echo mysqli_connect_error($this->link);exit;
            }
            mysqli_select_db($this->link,DB);
            mysqli_set_charset($this->link,CHARSET);
        }
       

        //处理查询结果的方法
        public function query($sql){
            $result=mysqli_query($this->link,$sql);
            if($result && mysqli_num_rows($result)>0){
                $userlist=array();
                while($row=mysqli_fetch_assoc($result)){
                    $userlist[]=$row;
                }
                return $userlist; 
            }else{
                echo 'sql语句有错误';
                return null;
            }
                
        }
        //获取数据库所有字段列表
        public function getfields(){
            //查看表信息的数据库语句就是我们得到数据字段的语句
            //DESC 表名
            $sql="DESC {$this->tabName}";
            // echo $sql;
            //发送sql语句
            $result=$this->query($sql);
            // var_dump($result);exit;
            //新建一个数组用来存储数据库的字段
            $fields = array();
            foreach($result as $value){
            // var_dump($value['Field']);
                $fields[]=$value['Field'];
            }
            
            //设置为缓存字段(接受字段)
            $this->allFields=$fields;
            // var_dump($this->allFields);
        }
        public function check($arr){
            // var_dump($arr);
            // var_dump($this->allFields);
            // exit;
            //传递过来数组需要拿出每个值和存储字段数组进行比较如果存在则保留如果不存在则删除
            foreach($arr as $key=>$value){
                // var_Dump($value);
                //判断得到的值是否在 存储字段的数组中如果在则保留 不在则删除
                if(!in_array($value,$this->allFields)){
                    unset($arr[$key]);
                }
            }
            return $arr;
        }
        //处理增删改的结果方法
        public function execute($sql){
            //打印sql语句内容
            $this->sql=$sql;
            $result= mysqli_query($this->link,$sql);
            if($result && mysqli_affected_rows($this->link)>0){
                //判断你是否是添加操作如果是添加操作返回操作的ID
                if(mysqli_insert_id($this->link)){
                    return mysqli_insert_id($this->link);
                }
                return true;
                
            }else{
                return false;
            }
        }

         // 析构
        public function __destruct(){
            mysqli_close($this->link);
        } 
    }
