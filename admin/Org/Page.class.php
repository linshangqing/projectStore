<?php
    //分页类
    class Page{
        protected $num; //每页显示数
        protected $total; //总条数
        public $amount; //总页数
        public $current;//当前页码数
        protected $offset; //偏移量
        Protected $limit;  //分页字符串

        public function __construct($total,$num){
            //1.每页显示数
            $this->num=$num;
            //2.总条数
            $this->total=$total;
            //3.总页数
            $this->amount=ceil($total/$num);
            //4.获取当前页码
            //初始化当前页码
            $this->init();
            //5.偏移量
            $this->offset=($this->current-1)*$num;
            //6.分页字符串
            $this->limit="{$this->offset},{$this->num}";
            
        }
        //初始化当前页码
        public function init(){
            //获取当前页码
            $this->current=empty($_GET['page'])?'1':$_GET['page'];
            //判断最小值
            if($this->current<1){
                $this->current=1;
            }
            //判断最大值
            if($this->current>$this->amount){
                $this->current=$this->amount;
            }
        }
        //获取分页按钮
        public function getbutton(){
            //需要将$_GET这个数组里的值赋给别的变量 prev next
            //判断第一次进来没有page的时候
            $_GET['page']=empty($_GET['page'])?'1':$_GET['page'];

            $first = $last = $prev = $next = $_GET;
            //首页
            $first['page']=1;
            //上一页
            $prev['page']=$prev['page']-1;
            //判断上一页的范围
            if($prev['page']<1){
                $prev['page'] =1;
            }
            //下一页
            $next['page']=$next['page']+1;
            
            if($next['page']>$this->amount){
                $next['page']=$this->amount;
            }
            //尾页
            $last['page']=$this->amount;
            if($last['page']>$this->amount){
                $last['page']=$this->amount;
            }
            //拼接路径
            $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
            // var_dump($url);
            //将数组中的每个单元以参数的形式拼接在一起
            $first=http_build_query($first);
            $prev=http_build_query($prev);
            $next=http_build_query($next);
            $last=http_build_query($last);
            // var_dump($first);

            //拼接一个上一页的完整路径
            $firstpath=$url.'?'.$first;
            $prevpath = $url.'?'.$prev;
            $nextpath = $url.'?'.$next;
            $lastpath = $url.'?'.$last;

            $str = '';
            $str .='<a href="'.$firstpath.'">首页</a>&nbsp;&nbsp;';
            $str .='<a href="'.$prevpath.'">上一页</a>&nbsp;&nbsp;';
            $str .='<a href="'.$nextpath.'">下一页</a>&nbsp;&nbsp;';
            $str .='<a href="'.$lastpath.'">尾页</a>&nbsp;&nbsp;';
            return $str;
        }

        public function __get($key){
            if($key == 'limit'){
                return $this->limit;
            }
            if($key == 'offset'){
                return $this->offset;
            }
        }
    }