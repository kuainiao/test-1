$(function(){
	
		$("#no_paychecks").click(function(){
		
		$("#no_paychecks").hide();
		$("#have_paychecks").show();
		$("#coins").text(parseInt($("#coins").text())+10);
		
		})
		
		
		
		
			
	})

window.onload=getdate;
function timer(obj,txt){
        
		  obj.text(txt);
} 

function getdate(){
var d=new Date()
var weekday=new Array(7)
weekday[0]="周日"
weekday[1]="周一"
weekday[2]="周二"
weekday[3]="周三"
weekday[4]="周四"
weekday[5]="周五"
weekday[6]="周六"
var getdate=weekday[d.getDay()];


var t=new Date();
var day=t.getDate()+"日";
var month=(t.getMonth() + 1)+"月";

timer($("#weekday"),getdate);
timer($("#month"),month);
timer($("#day"),day);


}


