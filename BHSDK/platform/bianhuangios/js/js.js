// JavaScript Document
$(function(){ 

	$(".pay-con>img").click(function(){
	
		$(".mobile-num").eq($(".pay-con>img").index(this)).show().siblings(".mobile-num").hide();
		$(".pay-con>img").eq($(".pay-con>img").index(this)).css({ border: "2px solid red"})
		.siblings().css({ border: "0px solid #ffffff"});	
		$(".coins ul>li").first().addClass("current").siblings().removeClass();
	 var payType = $(this).attr('rel');
         cardTypeCombine = (payType==3)?'zhifubao':payType;
        payType=(payType==3)?1:'szpay';
        $('input[name=payType]').val(payType);
	$('input[name=cardTypeCombine]').val(cardTypeCombine);	
	})
	
        $('#cardMoney>ul li').click(function(){
            $('#cardMoney>ul li').css({ border: "1px solid #0099ff"});
            $(this).css({ border: "2px solid red"});
            $('input[name=cardMoney]').val($(this).attr('value'));
        })
	$(".pay-con>img:gt(0)").click(function(){
	
		$("#coins ,#cardMoney").show();
	$("html,body").animate({scrollTop: $("#cardMoney").offset().top}, 500); 
		
	})
	$(".pay-con>img:eq(0)").click(function(){
	
		$("#coins ,#cardMoney").hide();
	
		
	})
		$(".coins ul>li").click(function(){
	
		$(".coins ul>li").eq($(".coins ul>li").index(this)).addClass("current").siblings().removeClass();
			$("html,body").animate({scrollTop: $("#coins").offset().top}, 500); 
	
	
	})


})
