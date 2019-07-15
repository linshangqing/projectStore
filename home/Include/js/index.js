$(document).ready(function(){
  $("#nav1").mousemove(function(){
	$(".nav span").addClass("nav1")
  })
  $("#nav2").mousemove(function(){
	$(".nav span").addClass("nav2")
  })
  $("#nav3").mousemove(function(){
	$(".nav span").addClass("nav3")
  })
  $("#nav4").mousemove(function(){
	$(".nav span").addClass("nav4")
  })
  $("#nav5").mousemove(function(){
	$(".nav span").addClass("nav5")
  })
  $("#nav6").mousemove(function(){
	$(".nav span").addClass("nav6")
  })
  $("#nav1").mouseout(function(){
	$(".nav span").removeClass("nav1")
  })
  $("#nav2").mouseout(function(){
	$(".nav span").removeClass("nav2")
  })
  $("#nav3").mouseout(function(){
	$(".nav span").removeClass("nav3")
  })
  $("#nav4").mouseout(function(){
	$(".nav span").removeClass("nav4")
  })
  $("#nav5").mouseout(function(){
	$(".nav span").removeClass("nav5")
  })
  $("#nav6").mouseout(function(){
	$(".nav span").removeClass("nav6")
  })
  $(".switch_ner li").mousemove(function(){
	$(this).css("width","400px").siblings().css("width","200px");
  })
  $(".switch_ner li").mousemove(function(){
	$(this).find(".switch_txt").css("width","200px").siblings(".switch_txt").css("width","0")
  })
  $(".go_top").click(function(){
	$("html, body").animate({scrollTop:0}, "slow");
  });
  $(".nav").find("a").each(function(){
    if (this.href == document.location.href || document.location.href.search(this.href) >= 0) {
    $(this).addClass("nav_in");
    }
  });
  window.onload = function(){
    if(document.location.pathname == "/E:/192.168.1.200/静态页面/沃晒创新WEB3模式/seckill.html"){
	  $(".nav span").css("margin-left","125px")
	}
	else if(document.location.pathname == "/E:/192.168.1.200/静态页面/沃晒创新WEB3模式/boutique.html"){
	  $(".nav span").css("margin-left","235px")
	}
	else if(document.location.pathname == "/E:/192.168.1.200/静态页面/沃晒创新WEB3模式/good.html"){
	  $(".nav span").css("margin-left","345px")
	}
	else if(document.location.pathname == "/E:/192.168.1.200/静态页面/沃晒创新WEB3模式/xinbi.html"){
	  $(".nav span").css("margin-left","455px")
	}
	else if(document.location.pathname == "/E:/192.168.1.200/静态页面/沃晒创新WEB3模式/funny.html"){
	  $(".nav span").css("margin-left","565px")
	}
	else{
	  $(".nav span").css("margin-left","15px")
	}
  }
})

$(function(){
  var $window = $(window),
  window_width = $window.width();
    $('#js_banner, #js_banner_img li').width(window_width);
  var urlParam = G.util.parse.url(),
	startFrame = 0;
  if (urlParam.search && ('banner' in urlParam.search)) {
	startFrame = (parseInt(urlParam.search['banner'])-1) || 0;
  }
  new $.Tab({/*添加圆点按钮*/
	target: $('#js_banner_img li'),
	effect: 'slide3d',
	animateTime: 1000,
	stay: 4000,
	playTo: startFrame,
	autoPlay: true,
	merge: true,
	prevBtn: $('#js_banner_pre'),
	nextBtn: $('#js_banner_next')
  });
  $('#js_banner_img').css('left','-' + (window_width * startFrame) + 'px');
})
/*
本代码由js代码收集并编辑整理;
尊重他人劳动成果;
转载请保留js代码链接 - www.jsdaima.com
*/