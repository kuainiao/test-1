$(function(){

$(".org_big_btn").click(function(){

	$(".personal_center_main_box").eq($(".org_big_btn").index(this)+1).show().siblings().hide();
	
})
})