$(function(){
	
	$(".personal_title>h4").click(function(){
	
		$(".personal_title>h4").index(this);
		
		$(".personal_title>h4").eq($(".personal_title>h4").index(this)).addClass("current").siblings().removeClass();
		$(".material-box").eq($(".personal_title>h4").index(this)).show().siblings().hide();
		
		
		
	})


})