// JavaScript Document
$(document).ready(function(){
	
	$("#sidenav li").click(function(){
    $("#sidenav li").eq($("#sidenav li").index(this)).addClass("active").siblings().removeClass();
    $(".resp-tabs-container").eq($("#sidenav li").index(this)).show().siblings(".resp-tabs-container").hide();});
		
	$(".hide_btn").click(function(){$(".content").toggle();});	
	/*$("#cancel").click(function(){alert("取消安装！！！");$("#fullbg,#dialog1").hide();});
	$("#confirm").click(function(){alert("安装支付宝安全支付服务！！！");$("#fullbg,#dialog1").hide();});*/
	$("#close1").click(function(){$("#fullbg,#dialog2").hide();});
	$("#close2").click(function(){$("#fullbg,#dialog3").hide();});
	
	 $("#alpay").keyup(function(){
					
        $("#light_coin").text($("#alpay").val()*100);
		
      });
	  $("#alpay1").keyup(function(){
					
        $("#light_coin1").text($("#alpay1").val()*100);
		
      });

//显示灰色 jQuery 遮罩层
/*function showBg() {
var bh = $(".wrap").height();
var bw = $(".wrap").width();
$("#fullbg").css({
height:bh,
width:bw,
display:"block"
});
$("#dialog1").show();
}
*/
function showBg1() {
var bh = $(".wrap").height();
var bw = $(".wrap").width();
$("#fullbg").css({
height:bh,
width:bw,
display:"block"
});
$("#dialog2").show();
}
function showBg2() {
var bh = $(".wrap").height();
var bw = $(".wrap").width();
$("#fullbg").css({
height:bh,
width:bw,
display:"block"
});
$("#dialog3").show();
}

