<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>



<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/base.css" />
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/quickLinks.css" />
    <link rel="stylesheet" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/purebox.css">
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<?php if ($this->_var['cat_style']): ?>
<link href="<?php echo $this->_var['cat_style']; ?>" rel="stylesheet" type="text/css" />
<?php endif; ?>
<link href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/select.css" rel="stylesheet" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS|<?php echo $this->_var['page_title']; ?>" href="<?php echo $this->_var['feed_url']; ?>" />

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js,cart_common.js,cart_quick_links.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,global.js,utils.js,compare.js,search_category_menu.js,jd_choose.js,pinyin.js,parabola.js')); ?>

<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/sc_common.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.tabso_yeso.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/rotate3di.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/scroll_city.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/asyLoadfloor.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/notLogin.js"></script>
</head>

<body class="<?php if ($this->_var['cate_info']['top_style_tpl'] == 1): ?>catagory_top_1<?php elseif ($this->_var['cate_info']['top_style_tpl'] == 2): ?>catagory_top_2<?php elseif ($this->_var['cate_info']['top_style_tpl'] == 3): ?>catagory_top_3<?php else: ?>catagory_top_0<?php endif; ?>">
    <?php echo $this->fetch('library/page_header_category.lbi'); ?>
	<?php if ($this->_var['cate_info']['top_style_tpl'] == 1): ?>
	<?php echo $this->fetch('library/top_style_tpl_1.lbi'); ?>
	<?php elseif ($this->_var['cate_info']['top_style_tpl'] == 2): ?>
	<?php echo $this->fetch('library/top_style_tpl_2.lbi'); ?>
	<?php elseif ($this->_var['cate_info']['top_style_tpl'] == 3): ?>
	<?php echo $this->fetch('library/top_style_tpl_3.lbi'); ?>
	<?php else: ?>
	<?php echo $this->fetch('library/top_style_tpl_0.lbi'); ?>
	<?php endif; ?>
    <?php 
$k = array (
  'name' => 'history_goods_pro',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
    <?php 
$k = array (
  'name' => 'user_menu_position',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
    <?php echo $this->fetch('library/page_footer.lbi'); ?>
    
    <script type="text/javascript">
    	$(".floor-misto").slide({mainCell:".ecsc-cp-r .floor-warpedg",effect:"left",autoPlay:false,autoPage:true,prevCell:".banner-prev",nextCell:".banner-next",pageStateCell:".pageState"});
    	
		//广告位提示调用
		$.adpos();
    </script>
</body>
</html>