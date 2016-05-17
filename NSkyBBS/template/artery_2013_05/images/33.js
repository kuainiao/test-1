jQuery(document).ready(function(){
				jQuery('.boxgrid.captionfull').hover(function(){
					jQuery(".cover", this).stop().animate({top:'125px'},{queue:false,duration:125});
				}, function() {
					jQuery(".cover", this).stop().animate({top:'162px'},{queue:false,duration:125});
				});
			});