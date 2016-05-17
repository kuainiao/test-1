// JavaScript Document
$(function(){
$("#guild_center>a").click(function(){

	$("#guild_center>a").index(this);
	
	$(".personal_center_main_box").eq($("#guild_center>a").index(this)).show().siblings().hide();


})
})