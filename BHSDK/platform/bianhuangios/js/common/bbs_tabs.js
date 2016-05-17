$(function(){
	
	$(".personal_title>h4").hover(function(){
	
		$(".personal_title>h4").index(this);
		
		$(".personal_title>h4").eq($(".personal_title>h4").index(this)).addClass("current").siblings().removeClass();
		$(".sub-mod").eq($(".personal_title>h4").index(this)).show().siblings().hide();
		
		
		
	})


})