$(function(){
$(".slide").click(function(){
		$(".slide").index(this);
		$(".message_box").eq($(".slide").index(this)).slideDown().siblings(".message_box").slideUp();
		
	})
})