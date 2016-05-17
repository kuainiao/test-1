jQuery(function(){

	// 我们的元素，以便更快地访问，并设置覆盖宽度
	var div = jQuery('div.sc_menu'), ul = jQuery('ul.sc_menu'), ulPadding = 15;
	
	// 菜单宽度
	var divWidth = div.width();
	
	// 删除滚动条
	div.css({overflow: 'hidden'});
	
	// 最后的图像容器
	var lastLi = ul.find('li:last-child');
	
	// 当用户将鼠标移动到菜单
	div.mousemove(function(e){
		// 当加载图像UL宽度增加, 因此，我们重新计算每个时间
		var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;	
		var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
		div.scrollLeft(left);
	});
	
});