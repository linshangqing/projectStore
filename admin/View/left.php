<?php
  session_start();
  // var_dump($_SESSION);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧导航menu</title>
<link href="../Include/css/css.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../Include/js/sdmenu.js"></script>
<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};
	// ]]>
</script>
<style type=text/css>
html{ SCROLLBAR-FACE-COLOR: #538ec6; SCROLLBAR-HIGHLIGHT-COLOR: #dce5f0; SCROLLBAR-SHADOW-COLOR: #2c6daa; SCROLLBAR-3DLIGHT-COLOR: #dce5f0; SCROLLBAR-ARROW-COLOR: #2c6daa;  SCROLLBAR-TRACK-COLOR: #dce5f0;  SCROLLBAR-DARKSHADOW-COLOR: #dce5f0; overflow-x:hidden;}
body{overflow-x:hidden; background:url(../Include/images/main/leftbg.jpg) left top repeat-y #f2f0f5; width:194px;}
</style>
</head>
<body onselectstart="return false;" ondragstart="return false;" oncontextmenu="return false;">
<div id="left-top">
	<div><img src="../Include/images/main/member.gif" width="44" height="44" /></div>
    <span>
      用户：<?php echo $_SESSION['admin']['name']?><br>
      角色:<?php
      switch($_SESSION['admin']['level']){
        case 0:
          echo '普通会员';
          break;
        case 1:
          echo '超级会员';
          break;
        case 2:
          echo '管理员';
          break;
        case 3:
          echo '超级管理员';
          break;
      }
      ?>
    </span>
</div>
    <div style="float: left" id="my_menu" class="sdmenu">
      <div class="collapsed">
        <span>用户管理</span>
        <a href="../index.php?c=user&a=index" target="mainFrame" onFocus="this.blur()">用户列表</a>
        <a href="../index.php?c=user&a=add" target="mainFrame" onFocus="this.blur()">添加用户</a>

      </div>
      <div>
        <span>分类管理</span>
        <a href="../index.php?c=type&a=index" target="mainFrame" onFocus="this.blur()">分类列表</a>
        <a href="../index.php?c=type&a=add" target="mainFrame" onFocus="this.blur()">添加分类</a>
        
      </div>
      <div>
        <span>商品管理</span>
        <a href="../index.php?c=goods&a=index" target="mainFrame" onFocus="this.blur()">商品列表</a>
        <a href="../index.php?c=goods&a=add" target="mainFrame" onFocus="this.blur()">添加商品</a>
      </div>
      <div>
        <span>订单管理</span>
        <a href="../index.php?c=order&a=index" target="mainFrame" onFocus="this.blur()">订单列表</a>
        
      </div>
      <div>
        <span>友情链接</span>
        <a href="../index.php?c=link&a=index" target="mainFrame" onFocus="this.blur()">链接列表</a>
        <a href="../index.php?c=link&a=add" target="mainFrame" onFocus="this.blur()">添加链接</a>
        
      </div>
      <div>
        <span>系统设置</span>
        <a href="./main.html" target="mainFrame" onFocus="this.blur()">基本信息</a>
      </div>
    </div>
</body>
</html>