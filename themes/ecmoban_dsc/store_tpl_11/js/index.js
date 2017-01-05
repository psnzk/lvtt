$(function(){

	//左侧导航展开效果
	$(".left-goods-sort .level1-arrow").click(function(){
		var wp = $(this).parents(".level1-wp");
		if( wp.hasClass("current")){
			wp.removeClass("current");
		}else{
			wp.addClass("current");
		}
	});

	//鼠标经过商品列表
	$(".right-goods-list li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	})

});
