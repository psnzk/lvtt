$(function(){

	//主轮播图
	$(".banner-main").slide({ 
		effect : "leftLoop" , 
		mainCell : ".bd ul",
		titCell : ".hd span",
		autoPlay : true
	});

	// 中间轮播图
	$(".banner-center").slide({
		effect : "leftLoop",
		mainCell : ".bd ul",
		autoPlay : true
	});


});