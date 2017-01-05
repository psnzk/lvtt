<div class="nav">
	<div class="w1200">
		<div class="categorys channel food">
			<div class="dt"><?php if ($this->_var['cate_info']['cat_icon']): ?><i class="cat_icon"><img src="<?php echo $this->_var['cate_info']['cat_icon']; ?>" alt="图标" /></i><?php else: ?><i class="icon food-icon"></i><?php endif; ?><?php echo $this->_var['cate_info']['cat_name']; ?></div>
			<div class="dd">
				<div class="cata-nav" id="parent-cata-nav">
					<?php $_from = $this->_var['categories_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['child']['iteration']++;
?>
					<div class="item" data-catid="<?php echo $this->_var['cat']['id']; ?>">
						<div class="item-left">
							<div class="cata-nav-name" data-parentcat="<?php echo $this->_var['cat']['id']; ?>" parent_eveval="0">
								<h3><a href="<?php echo $this->_var['cat']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['cat']['name']); ?></a></h3>
								<div class="ext">
									<?php $_from = $this->_var['cat']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['no']['iteration']++;
?>
									<?php if ($this->_foreach['no']['iteration'] < 4): ?>
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
				<?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_31230100_1483523815');$this->_foreach['nav_middle_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_middle_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav_0_31230100_1483523815']):
        $this->_foreach['nav_middle_list']['iteration']++;
?>
				<li><a href="<?php echo $this->_var['nav_0_31230100_1483523815']['url']; ?>" <?php if ($this->_var['nav_0_31230100_1483523815']['active'] == 1): ?>class="selected"<?php endif; ?> <?php if ($this->_var['nav_0_31230100_1483523815']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_0_31230100_1483523815']['name']; ?></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	</div>
</div>
<div class="banner">
	<div class="classify-banner">
		<div class="bd" data-adposname="顶级分类页（食品模板）banner广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
		   <?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['top_style_food_banner'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
		</div>
		<div class="ecsc-warp">
			<div class="hd"><ul></ul></div>
            <?php if ($this->_var['cate_top_group_goods']): ?>
			<div class="banner-group">
				<div class="group-main">
					<ul>
						<?php $_from = $this->_var['cate_top_group_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['group']['iteration']++;
?>
						<li>
							<div class="p-img"><a href="group_buy.php?act=view&id=<?php echo $this->_var['goods']['act_id']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" height="160" width="160"/></a></div>
							<div class="p-name"><a href="group_buy.php?act=view&id=<?php echo $this->_var['goods']['act_id']; ?>" class="lh36" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
							<div class="p-price"><em>￥</em><?php echo $this->_var['goods']['price_ladder']['0']['price']; ?></div>
							<a href="group_buy.php?act=view&id=<?php echo $this->_var['goods']['act_id']; ?>" target="_blank" class="btn">立即团 ></a>
							<div class="num">已售：<?php echo $this->_var['goods']['sales_volume']; ?></div>
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>					
					</ul>
				</div>
				<a href="javascript:void(0);" class="done-prev"></a>
				<a href="javascript:void(0);" class="done-next"></a>
			</div>
            <?php endif; ?>
		</div>
	</div>
</div>
<div id="content">
	<div class="lazy-ecsc-warp">
		<div class="ecsc-berserk w1200">
			<div class="berserk-product">
				<ul>
					<?php $_from = $this->_var['cate_top_new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['new'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['new']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['new']['iteration']++;
?>
					<?php if ($this->_foreach['new']['iteration'] < 4): ?>
					<li<?php if ($this->_foreach['new']['iteration'] == 3): ?> class="last"<?php endif; ?>>
						<div class="p-img"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="115" height="115"></div>
						<div class="p-right">
							<div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
							<div class="p-price">
								<?php if ($this->_var['goods']['promote_price'] != ''): ?>
								<?php echo $this->_var['goods']['promote_price']; ?>
								<?php else: ?>
								<?php echo $this->_var['goods']['shop_price']; ?>
								<?php endif; ?>
							</div>
							<div class="num">已售：<?php echo $this->_var['goods']['sales_volume']; ?></div>
							<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" class="btn2">去抢购 ></a>
						</div>
					</li>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<ul>
					<?php $_from = $this->_var['cate_top_best_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['best'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['best']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['best']['iteration']++;
?>
					<?php if ($this->_foreach['best']['iteration'] < 4): ?>
					<li<?php if ($this->_foreach['new']['iteration'] == 3): ?> class="last"<?php endif; ?>>
						<div class="p-img"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="115" height="115"></div>
						<div class="p-right">
							<div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
							<div class="p-price">
								<?php if ($this->_var['goods']['promote_price'] != ''): ?>
								<?php echo $this->_var['goods']['promote_price']; ?>
								<?php else: ?>
								<?php echo $this->_var['goods']['shop_price']; ?>
								<?php endif; ?>
							</div>
							<div class="num">已售：<?php echo $this->_var['goods']['sales_volume']; ?></div>
							<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" class="btn2">去抢购 ></a>
						</div>
					</li>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>					
				</ul>
			</div>
			<ul class="berserk-hd">
				<li>今日新品</li>
				<li>精品推荐</li>
			</ul>
		</div>
		<div class="ecsc-hot w1200">
			<div class="hot-title"><h1>热门</h1><span>Hot</span></div>
			<div class="hot-content">
            	<div class="hot-left-ado" data-adposname="顶级分类页（食品模板）热门区域广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
                	<?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['top_style_food_hot'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
                </div>
				<div class="hot-right-pro">
                <div class="hot-items">
				<?php $_from = $this->_var['cate_top_hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_one');$this->_foreach['goods_one'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_one']['total'] > 0):
    foreach ($_from AS $this->_var['goods_one']):
        $this->_foreach['goods_one']['iteration']++;
?>
				<?php if (($this->_foreach['goods_one']['iteration'] - 1) < $this->_foreach['goods_one']['total'] / 6): ?>
					<div class="hot-item">
						<div class="hot-product">
							<ul>
							<?php $_from = $this->_var['cate_top_hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['hot'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hot']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['hot']['iteration']++;
?>
							<?php if (($this->_foreach['hot']['iteration'] - 1) > ( ($this->_foreach['goods_one']['iteration'] - 1) ) * 6 - 1 && ($this->_foreach['hot']['iteration'] - 1) < ( ($this->_foreach['goods_one']['iteration'] - 1) + 1 ) * 6): ?>
								<li>
									<div class="hot-product-left">
										<div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="115" height="115"></a></div>
										<i class="icon hot-icon"></i>
									</div>
									<div class="hot-product-right">
										<div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
										<div class="p-price">
											<?php if ($this->_var['goods']['promote_price'] != ''): ?>
											<?php echo $this->_var['goods']['promote_price']; ?>
											<?php else: ?>
											<?php echo $this->_var['goods']['shop_price']; ?>
											<?php endif; ?>										
										</div>
										<div class="original-price"><?php echo $this->_var['goods']['market_price']; ?></div>
									</div>
									<div class="brand"></div>
								</li>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
				<a href="javascript:void(0);" class="prev hot-prev"></a>
				<a href="javascript:void(0);" class="next hot-next"></a>
                </div>
			</div>
		</div>

		
		<div id="cat_top_lit"></div>
		
		
		<div class="clearance w1200">
			<div class="ec-title">
				<h1>清仓</h1>
				<span>Clearance</span>
			</div>
			<div class="clearance-content">
				<div class="clearance-content-left">
				<?php $_from = $this->_var['cate_top_promote_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'promote');$this->_foreach['promote'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['promote']['total'] > 0):
    foreach ($_from AS $this->_var['promote']):
        $this->_foreach['promote']['iteration']++;
?>
				<?php if ($this->_foreach['promote']['iteration'] < 5): ?>
					<div class="slider-desc<?php if ($this->_foreach['promote']['iteration'] == 3 || $this->_foreach['promote']['iteration'] == 4): ?> last<?php endif; ?>">
						<div class="desc-left"><a href="<?php echo $this->_var['promote']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['promote']['thumb']; ?>" width="120" height="120"></a></div>
						<div class="desc-right">
							<div class="p-name"><a href="<?php echo $this->_var['promote']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['promote']['name']; ?></a></div>
							<div class="p-price">
								<?php if ($this->_var['promote']['promote_price'] != ''): ?>
								<?php echo $this->_var['promote']['promote_price']; ?>
								<?php else: ?>
								<?php echo $this->_var['promote']['shop_price']; ?>
								<?php endif; ?>							
							</div>
							<div class="original-price"><?php echo $this->_var['promote']['market_price']; ?></div>
						</div>
					</div>
				<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
				<div class="clearance-content-right">
					<ul>
					<?php $_from = $this->_var['cate_top_promote_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'promote');$this->_foreach['promote'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['promote']['total'] > 0):
    foreach ($_from AS $this->_var['promote']):
        $this->_foreach['promote']['iteration']++;
?>
					<?php if ($this->_foreach['promote']['iteration'] > 4 && $this->_foreach['promote']['iteration'] < 8): ?>
						<li>
							<div class="p-img"><a href="<?php echo $this->_var['promote']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['promote']['thumb']; ?>" width="138" height="138"></a></div>
							<div class="p-name"><a href="<?php echo $this->_var['promote']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['promote']['name']; ?></a></div>
							<div class="p-price">
								<?php if ($this->_var['promote']['promote_price'] != ''): ?>
								<?php echo $this->_var['promote']['promote_price']; ?>
								<?php else: ?>
								<?php echo $this->_var['promote']['shop_price']; ?>
								<?php endif; ?>							
							</div>
							<div class="original-price"><?php echo $this->_var['promote']['market_price']; ?></div>
							<a href="<?php echo $this->_var['promote']['url']; ?>" target="_blank" class="btn3">去看看 ></a>
						</li>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<input name="region_id" value="<?php echo $this->_var['region_id']; ?>" type="hidden">
<input name="area_id" value="<?php echo $this->_var['area_id']; ?>" type="hidden">
<input name="cat_id" value="<?php echo $this->_var['cate_info']['cat_id']; ?>" type="hidden">
<script type="text/javascript">
	$.slidehd(".hot-left-ado .bd li",".hot-left-ado .hd");

	//banner广告轮播调用
	$(".classify-banner").slide({titCell:".hd ul",mainCell:".bd ul",effect:"fold",interTime:3500,delayTime:500,autoPlay:true,autoPage:true,trigger:"click"});
	//banner团购滚动
	$(".banner-group").slide({mainCell:".group-main ul",effect:"left",pnLoop:false,autoPlay:false,autoPage:true,scroll:1,vis:1,prevCell:".done-prev",nextCell:".done-next"});
	//疯狂抢购
	$(".ecsc-berserk").slide({titCell:".berserk-hd li",mainCell:".berserk-product"});
	
	//热门hot
	if($('.hot-left-ado .bd li').length>1){
		$(".hot-left-ado").slide({titCell:".hd ul",mainCell:".bd ul",effect:"left",pnLoop:false,autoPlay:false,autoPage:true})
	}
	
	$(".hot-right-pro").slide({mainCell:".hot-items",effect:"left",pnLoop:false,autoPlay:false,scroll:1,vis:1,prevCell:".hot-prev",nextCell:".hot-next"})

	function loadCategoryTop(key){
		var Floor = $("#floor_"+key);
		
		//楼层js
		var objbd = Floor.find(".slider-wrap .bd li");
		var objhd = Floor.find(".slider-wrap .hd");
		
		$.slidehd(objbd,objhd);
		
		if(objbd.length>1){
			Floor.find(".slider-wrap").slide({titCell:".hd ul",mainCell:".bd ul",effect:"left",pnLoop:false,autoPlay:false,autoPage:true});
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


	