<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商品搜索</title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/base.css" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<link href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/select.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/quickLinks.css" />
<link rel="alternate" type="application/rss+xml" title="RSS|<?php echo $this->_var['page_title']; ?>" href="<?php echo $this->_var['feed_url']; ?>" />
<link rel="stylesheet" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/purebox.css">

<link rel="stylesheet" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/perfect-scrollbar/perfect-scrollbar.min.css">


<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js,cart_common.js,cart_quick_links.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,global.js,utils.js,compare.js,search_category_menu.js,jd_choose.js,pinyin.js,parabola.js')); ?>

<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/sc_common.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.tabso_yeso.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.yomi.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/rotate3di.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/scroll_city.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/notLogin.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/asyLoadfloor.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<style>.suspension{display:none;}</style>
</head>

<body>
<?php echo $this->fetch('library/page_header_w1390.lbi'); ?>
<div class="ecsc-breadcrumb w1390">
    <?php echo $this->fetch('library/ur_here.lbi'); ?>
</div>

<div id="filter">
    <div class="filter-section-wrapper mt-component-2 w1390">
		<?php echo $this->fetch('library/category_screening.lbi'); ?>
    </div>
    
</div>

<div id="content" class="w1390">
	
    <?php echo $this->fetch('library/goods_list.lbi'); ?>
    
</div>
<?php if (! $this->_var['category_load_type']): ?>

<?php echo $this->fetch('library/pages.lbi'); ?>

<?php endif; ?>
<?php 
$k = array (
  'name' => 'user_menu_position',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?> 
    	 
<?php echo $this->fetch('library/duibi.lbi'); ?>

<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.purebox.js"></script>
<input name="script_name" value="<?php echo $this->_var['script_name']; ?>" type="hidden" />
<input name="cur_url" value="<?php echo $this->_var['cur_url']; ?>" type="hidden" />
<script type="text/javascript">
	//异步加载更多商品 by wu start	
	<?php if ($this->_var['category_load_type']): ?>
		var query_string = '<?php echo $this->_var['query_string']; ?>';
		$.goodsLoad('.goods_network_list .goods-list','.gl-item','.goods-spread',query_string,0);
	<?php endif; ?>
	//异步加载更多商品 by wu end
	
	//商品列表页 商品小图轮播 图片数量大于6个
	sildeImg(0);
	
	$(function(){
		var obj = $("#filter-sortbar .styles").find(".item");
		var imtes = $(".category_left");
		obj.click(function(){
			var index = $(this).index();
			$(this).addClass("current").siblings().removeClass("current");
			imtes.find(".car_goods_list").eq(index).show().siblings(".car_goods_list").hide();
			//需要开启异步加载
			<?php if ($this->_var['category_load_type']): ?>
				if(index == 1){
					$.goodsLoad('.goods_switch_list .goods-list','.item','.goods-spread',query_string,1);
				}
			<?php endif; ?>
		});
		
		$(".zimu_list").hover(function(){
			$(".zimu_list").perfectScrollbar();
		});
		
		$(".wrap_brand").hover(function(){
			$(".extend .wrap_brand").perfectScrollbar();
		});
	});
</script>
</body>
</html>