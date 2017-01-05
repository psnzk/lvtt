<div class="nav">
	<div class="w1200">
		<div class="categorys channel electronic">
			<div class="dt"><?php if ($this->_var['cate_info']['cat_icon']): ?><i class="cat_icon"><img src="<?php echo $this->_var['cate_info']['cat_icon']; ?>" alt="图标" /></i><?php else: ?><i class="icon electronic-icon"></i><?php endif; ?><?php echo $this->_var['cate_info']['cat_name']; ?></div>
			<div class="dd">
				<div class="cata-nav" id="parent-cata-nav">
					<?php $_from = $this->_var['categories_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['child']['iteration']++;
?>
					<div class="item" data-catid="<?php echo $this->_var['cat']['id']; ?>">
						<div class="item-left">
							<div class="cata-nav-name" data-parentcat="<?php echo $this->_var['cat']['id']; ?>" parent_eveval="0" >
								<h3><a href="<?php echo $this->_var['cat']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['cat']['name']); ?></a></h3>
                                <div class="ext">
                                <?php $_from = $this->_var['cat']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['no']['iteration']++;
?>
                                <?php if (($this->_foreach['no']['iteration'] - 1) < 3): ?>
                                <a href="<?php echo $this->_var['child']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['child']['name']); ?></a>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </div>
							</div>
							<b>></b>
						</div>
						<div class="cata-nav-layer">
							<div class="cata-content">
								<h1><?php echo htmlspecialchars($this->_var['cat']['name']); ?></h1>
								<div class="zfenlei">
									<?php $_from = $this->_var['cat']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['no']['iteration']++;
?>
									<a href="<?php echo $this->_var['child']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['child']['name']); ?></a>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</div>
								<div class="i-brand" id="brands_<?php echo $this->_var['cat']['id']; ?>">
							
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
			</div>
		</div>
		<div class="navitems">
			<ul>
				<li><a href="index.php" <?php if ($this->_var['navigator_list']['config']['index'] == 1): ?>class="selected"<?php endif; ?>>首页</a></li>
				<?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_40679100_1483523495');$this->_foreach['nav_middle_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_middle_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav_0_40679100_1483523495']):
        $this->_foreach['nav_middle_list']['iteration']++;
?>
				<li><a href="<?php echo $this->_var['nav_0_40679100_1483523495']['url']; ?>" <?php if ($this->_var['nav_0_40679100_1483523495']['active'] == 1): ?>class="selected"<?php endif; ?> <?php if ($this->_var['nav_0_40679100_1483523495']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_0_40679100_1483523495']['name']; ?></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	</div>
</div>
<div class="banner electronic">
	<div class="classify-banner">
		<div class="bd" data-adposname="顶级分类页（家电模板）banner广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
		<?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['top_style_elec_banner'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>  
		</div>
		<div class="ecsc-warp">
			<div class="banner-btn">
				<a href="javascript:void(0);" class="banner-prev"></a>
				<a href="javascript:void(0);" class="banner-next"></a>
			</div>
			<div class="hd"><ul></ul></div>
			<div class="banner-switch">
				<div class="switch-tab">
					<ul>
						<?php $_from = $this->_var['cat_detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['child']['iteration']++;
?>
						<li class="<?php if (($this->_foreach['child']['iteration'] <= 1)): ?>on<?php endif; ?>"><?php echo htmlspecialchars($this->_var['cat']['name']); ?><i></i></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<div class="switch-content" id="parent_cate_nav" >
					<?php $_from = $this->_var['cat_detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['detail'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['detail']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['detail']['iteration']++;
?>							
					<div class="switch-item" style="display:<?php if (($this->_foreach['detail']['iteration'] <= 1)): ?>block<?php else: ?>none<?php endif; ?>;">							
						<div class="switch-warp">
							<ul>
								<?php $_from = $this->_var['cat']['goods_detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_detail');if (count($_from)):
    foreach ($_from AS $this->_var['goods_detail']):
?>
								<li>
									<div class="switch-img"><a href="<?php echo $this->_var['goods_detail']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods_detail']['thumb']; ?>" width="135" height="135"></a></div>
									<div class="switch-name"><a href="<?php echo $this->_var['goods_detail']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods_detail']['name']); ?></a></div>
									<div class="switch-price">
									<?php if ($this->_var['goods_detail']['promote_price'] != 0 && $this->_var['goods_detail']['promote_price'] != ''): ?>
											 <?php echo $this->_var['goods_detail']['promote_price']; ?>
										<?php else: ?>
											 <?php echo $this->_var['goods_detail']['shop_price']; ?>
										<?php endif; ?>
									</div>
								</li>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</ul>
							<a href="javascript:void(0);" class="done-prev"></a>
							<a href="javascript:void(0);" class="done-next"></a>
						</div>							
					</div>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
			</div>

		</div>
	</div>
</div>

<div id="content">
	<div class="lazy-ecsc-warp">
		<div class="ecsc-jd-brand w1200">
			<div class="ec-title">
				<h1>品牌</h1>
				<span>Brands</span>
			</div>
			<div class="brand-list" data-adposname="顶级分类页（家电模板）品牌广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
			<?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['top_style_elec_brand'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
			</div>
		</div>		
        
        
		<div id="cat_top_lit"></div>
		
        
        <div class="w1200 floor" id="floor_loading" style="padding:120px 0px;">
            <div style="width:189px; height:150px; margin:auto;"><img src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/loading.gif"></div>
        </div>
        
		<div class="have-a-look w1200">
			<div class="ec-title">
				<h1>随便看看</h1>
				<span>Have a look</span>
				<a href="javascript:void(0)" class="ec-huan"><i class="icon"></i>换一组</a>
			</div>
			<div class="ecsc-ps-list">
				<ul>
				<?php $_from = $this->_var['havealook']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'look');if (count($_from)):
    foreach ($_from AS $this->_var['look']):
?>
				<li class="ecsc-ps-item">
					<div class="ecsc-ps-photo"><a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank"><img src="<?php echo $this->_var['look']['thumb']; ?>" width="184" height="184"></a></div>
					<div class="ecsc-ps-c">
						<div class="p-name"><a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank"><?php echo $this->_var['look']['name']; ?></a></div>
						<div class="p-price"><!--<em>￥</em>-->
							<?php if ($this->_var['goods']['promote_price'] != ''): ?>
							<?php echo $this->_var['look']['promote_price']; ?>
							<?php else: ?>
							<?php echo $this->_var['look']['shop_price']; ?>
							<?php endif; ?>
						</div>
						<a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank" class="btn2">去看看 ></a>
					</div>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
		</div>	
		<?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['top_style_elec_foot'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
	</div>
</div>
<input name="region_id" value="<?php echo $this->_var['region_id']; ?>" type="hidden">
<input name="area_id" value="<?php echo $this->_var['area_id']; ?>" type="hidden">
<input name="cat_id" value="<?php echo $this->_var['cate_info']['cat_id']; ?>" type="hidden">

<script type="text/javascript">
	//banner广告轮播调用
	$(".classify-banner").slide({mainCell:".bd ul",effect:"fold",pnLoop:false,autoPlay:false,autoPage:true,prevCell:".banner-prev",nextCell:".banner-next"});
	
	//banner上推荐轮播切换
	$(".switch-tab ul").tabso({cntSelect:".switch-content",tabEvent:"hover",tabStyle:"normal",onStyle:"on"});
	$(".switch-item").slide({mainCell:".switch-warp ul",effect:"left",pnLoop:false,autoPlay:false,autoPage:true,scroll:1,vis:5,prevCell:".done-prev",nextCell:".done-next"});
	
	//异步加载每个楼层需加载的js
	function loadCategoryTop(key){
		var Floor = $("#floor_"+key);
		
		var objbd = Floor.find(".layout-l-warp .bd li");
		var objhd = Floor.find(".layout-l-warp .hd");
		
		$.slidehd(objbd,objhd);
		
		//判断楼层左侧广告是否大于1张，大于则轮播显示图片
		if(objbd.length>1){
			Floor.find(".layout-l-warp").slide({titCell:".hd ul",mainCell:".bd ul",effect:"left",autoPlay:true,autoPage:true});
		}
		
		/*
		Floor.find("img.lazy").lazyload({
			effect : "fadeIn"
		});
		*/ 
	}
	
	//楼层异步加载封装函数
	var tpl = '<?php echo $this->_var['cate_info']['top_style_tpl']; ?>'; //顶级分类页模板id
	$.catTopLoad(tpl);
</script>