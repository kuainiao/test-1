$(function(){ 
//选项
$("#search_select").click(function(){
		$("#select_search1").removeClass("hide").addClass("show");
	
	});
	$("#select_search1 li").click(function(){
		

		
		$("#search_select").text( $(this).text());
		$("#select_search1").removeClass("show").addClass("hide");
		 
	});
	
//input焦点	
$("#search_text").focus(function(){
	
	$("#search_text").val("");
	
})
$("#search_text").blur(function(){
	
	
	if($("#search_text").val()=="")
	{$("#search_text").val("请输入游戏名or公会名");}
	
})
$("#searchapp").focus(function(){
	
	$("#searchapp").val("");
	
});
$("#searchapp").blur(function(){
	
	
	if($("#searchapp").val()=="")
	{$("#searchapp").val("输入APP名称...");}
	
})

$("#find").focus(function(){
	
	$("#find").val("");
	
});
$("#find").blur(function(){
	
	
	if($("#find").val()=="")
	{$("#find").val("请输入关键字");}
	
})


//网站导航
$(".top:eq(0)").find(".web_subnav").hover(

function(){

	$(this).find(".sunnav_box").show();
	$(this).children("a").addClass("current");
	
	},function () {
		$(this).find(".sunnav_box").hide();
		$(this).children("a").removeClass("current");
		
	})

$(".top:eq(0)").find(".contrl").hover(

function(){

	$(this).find(".menuHide").show();
	$(this).children("a").addClass("current");
	
	},function () {
		$(this).find(".menuHide").hide();
		$(this).children("a").removeClass("current");
		
	})

//图片推广


		$('.speard-con:eq(0)').delegate('a','mouseenter',function(){
		$(this).children('.meng').stop().animate({'bottom':"0"},300);
	});
	$('.speard-con:eq(0)').delegate('a','mouseleave',function(){
		$(this).children('.meng').stop().animate({'bottom':"-100%"},300);
	});


//分类切换
$(".game_class_nav_tab>li").hover(function(){

	var game_index=$(".game_class_nav_tab>li").index(this);
	$(".game_class_nav_tab>li").eq(game_index).addClass("game_class_tab1").siblings().removeClass();
	$(".hide_game_recomm").eq(game_index).show().siblings().hide();
	
})
})
