
<?php if ($this->_var['tpl'] == 1): ?>
<?php if ($this->_var['one_cate_child']): ?>
<div class="floor w1200" id="floor_<?php echo $this->_var['rome_number']; ?>">
    <div class="floor-container">
        <div class="floor-title">
            <div class="floor-wm-num floor_<?php echo $this->_var['one_cate_child']['id']; ?>"><?php echo $this->_var['rome_number']; ?>F</div>
            <h1><?php echo $this->_var['one_cate_child']['name']; ?></h1>
            <ul class="tab">
            <?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_12800100_1483523499');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_12800100_1483523499']):
        $this->_foreach['no']['iteration']++;
?>
            <?php if ($this->_foreach['no']['iteration'] < 6): ?>
              <li <?php if ($this->_foreach['no']['iteration'] == 1): ?> class="on" <?php endif; ?>><?php echo htmlspecialchars($this->_var['child_0_12800100_1483523499']['name']); ?><i></i></li>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="floor-content">
            
            <div class="floor-ps-item">
            <div class="floor-left">
                <div class="floor-left-banner" data-adposname="顶级分类页（女装模板）楼层左侧广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>                       	
                    <?php echo $this->_var['cat_top_floor_ad']; ?>
                </div>
            </div>
            <div class="floor-right">
            <?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_12881200_1483523499');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_12881200_1483523499']):
        $this->_foreach['no']['iteration']++;
?>
            <?php if ($this->_foreach['no']['iteration'] < 6): ?>
                <div class="ecsc-cp-r ecsc-cp-tabs" <?php if ($this->_foreach['no']['iteration'] > 1): ?> style="display:none;" <?php endif; ?>>
                <?php if ($this->_var['child_0_12881200_1483523499']['goods_list']): ?>
                    <ul>
                    <?php $_from = $this->_var['child_0_12881200_1483523499']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                        <li>
                            <div class="product-desc">
                                <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="136" height="136"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
                                <div class="ecsc-bp">
                                    <div class="p-price"><?php echo $this->_var['goods']['shop_price']; ?></div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php if ($this->_var['one_cate_child']['brands']): ?>
            <div class="floor-brand">
                <ul>
                <?php $_from = $this->_var['one_cate_child']['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');$this->_foreach['b'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['b']['total'] > 0):
    foreach ($_from AS $this->_var['brand']):
        $this->_foreach['b']['iteration']++;
?>
                <?php if ($this->_foreach['b']['iteration'] < 11): ?>
                    <li><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" height="45"></a></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>


<?php if ($this->_var['tpl'] == 2): ?>
<?php if ($this->_var['one_cate_child']): ?>
<?php echo $this->_var['top_style_elec_row']; ?>
<div class="floor jd-floor w1200" id="floor_<?php echo $this->_var['rome_number']; ?>">
	<div class="floor-container">	
        <div class="mt">
            <div class="floor-jd-num floor<?php echo $this->_var['rome_number']; ?> floor_<?php echo $this->_var['one_cate_child']['id']; ?>"></div>
            <h1><?php echo $this->_var['one_cate_child']['name']; ?></h1>
            <ul class="tab">
            <?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_13093900_1483523499');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_13093900_1483523499']):
        $this->_foreach['no']['iteration']++;
?>
            <?php if ($this->_foreach['no']['iteration'] < 4): ?>
                <li class="<?php echo $this->_var['class_num'][($this->_foreach['no']['iteration'] - 1)]; ?>"><?php echo htmlspecialchars($this->_var['child_0_13093900_1483523499']['name']); ?><i></i></li>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="mc jd-layout">
            <div class="layout-l">
                <?php $_from = $this->_var['one_cate_child']['goods_hot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['hot'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hot']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['hot']['iteration']++;
?>
                <?php if (($this->_foreach['hot']['iteration'] - 1) < 1): ?>
                <div class="layout-l-cp">
                    <div class="cp-l"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="190" height="190"></a><i class="icon hot-icon"></i></div>
                    <div class="cp-r">
                        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
                        <div class="p-price"><em>现价:</em><?php echo $this->_var['goods']['shop_price']; ?></div>
                        <div class="original-price">原价:<?php echo $this->_var['goods']['market_price']; ?></div>
                        <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" class="btn2">去抢购 ></a>
                    </div>
                </div>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <div class="layout-l-adv">
                    <div class="layout-l-warp" data-adposname="顶级分类页（家电模板）楼层左侧广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
                        <?php echo $this->_var['top_style_elec_left']; ?>
                    </div>
                </div>
            </div>
            
	    <div class="layout-items">
            <?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_13217200_1483523499');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_13217200_1483523499']):
        $this->_foreach['no']['iteration']++;
?>
            <?php if ($this->_foreach['no']['iteration'] < 4): ?>
            <div class="layout-item ecsc-cp-tabs" <?php if ($this->_foreach['no']['iteration'] > 1): ?> style="display:none;" <?php endif; ?> >
                <div class="layout-r">
                    <div class="layout-r-cp" cat_id="<?php echo $this->_var['child_0_13217200_1483523499']['id']; ?>">
                    <?php if ($this->_var['child_0_13217200_1483523499']['goods_list']): ?>
                        
                        <ul>
                            <?php $_from = $this->_var['child_0_13217200_1483523499']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
                            <li>
                            <div class="product-desc">
                            <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="135" height="135"></a></div>
                            <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
                            <div class="ecsc-bp"><div class="p-price"><?php echo $this->_var['goods']['shop_price']; ?></div></div>
                            </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                    <?php endif; ?>
                    </div>
                    
                    <a href="javascript:void(0)" class="ec-huan" onclick="changeShow(<?php echo $this->_var['child_0_13217200_1483523499']['id']; ?>,<?php echo $this->_var['tpl']; ?>)"><i class="icon"></i>换一组</a>
                    <div class="floor-brand layout-r-brand">
                        <div class="bd-brand-list">
                            <ul>
                            <?php $_from = $this->_var['one_cate_child']['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');$this->_foreach['b'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['b']['total'] > 0):
    foreach ($_from AS $this->_var['brand']):
        $this->_foreach['b']['iteration']++;
?>
                                <?php if ($this->_foreach['b']['iteration'] < 9): ?>
                                <li<?php if (($this->_foreach['b']['iteration'] == $this->_foreach['b']['total']) || $this->_foreach['b']['iteration'] == 8): ?> class="last"<?php endif; ?>><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" width="100" height="45" ></a></li>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>               	
	    </div>
        </div>
	</div>			
</div>
<?php endif; ?>
<?php endif; ?>


<?php if ($this->_var['tpl'] == 3): ?>
<?php if ($this->_var['one_cate_child']): ?>
<?php echo $this->_var['top_style_food_row']; ?>
<div class="floor w1200" id="floor_<?php echo $this->_var['rome_number']; ?>">
	<div class="floor-container">
	<div class="mt">
		<div class="floor-num"><?php echo $this->_var['rome_number']; ?>F</div>
		<h1><?php echo $this->_var['cat']['name']; ?></h1>
		<ul class="tab">
		<?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_13409300_1483523499');$this->_foreach['child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_13409300_1483523499']):
        $this->_foreach['child']['iteration']++;
?>
			<?php if (($this->_foreach['child']['iteration'] - 1) < 5): ?>
			<li<?php if ($this->_foreach['child']['iteration'] == 1): ?> class="on"<?php endif; ?>><?php echo $this->_var['child_0_13409300_1483523499']['name']; ?></li>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<div class="mc_<?php echo $this->_var['rome_number']; ?>">
		<div class="layout">
			<div class="layout-l">
				<div class="book-pannel-slider">
					<div class="slider-wrap" data-adposname="顶级分类页（食品模板）楼层左侧广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
						<?php echo $this->_var['top_style_food_left']; ?>
					</div>
				</div>
				<?php $_from = $this->_var['one_cate_child']['goods_hot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
				<?php if ($this->_foreach['goods']['iteration'] == 1): ?>
				<div class="slider-desc">					
					<div class="desc-left"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="120" height="120"></a></div>					
					<div class="desc-right">
						<div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
						<div class="p-price"><?php echo $this->_var['goods']['shop_price']; ?></div>
						<div class="original-price"><?php echo $this->_var['goods']['market_price']; ?></div>
						<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" class="btn2">去看看 &gt;</a>
					</div>
				</div>
                <?php endif; ?>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
            
            <div class="layout-mian">
                <?php $_from = $this->_var['one_cate_child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_13533800_1483523499');$this->_foreach['child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_13533800_1483523499']):
        $this->_foreach['child']['iteration']++;
?>
                <?php if (($this->_foreach['child']['iteration'] - 1) < 5): ?>
                <div class="layout-c-info ecsc-cp-tabs" <?php if ($this->_foreach['child']['iteration'] > 1): ?> style="display:none;" <?php endif; ?>>
                    <div class="layout-c" cat_id="<?php echo $this->_var['child_0_13533800_1483523499']['id']; ?>">
                        <ul>
                        <?php $_from = $this->_var['child_0_13533800_1483523499']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] > 0 && $this->_foreach['goods']['iteration'] < 7): ?>
                            <li>
                                <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="142" height="142"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
                                <div class="p-price">
                                <?php if ($this->_var['goods']['promote_price'] != 0 && $this->_var['goods']['promote_price'] != ''): ?>
                                <?php echo $this->_var['goods']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['goods']['shop_price']; ?>
                                <?php endif; ?>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                    </div>
                    <a href="javascript:void(0)" class="ec-huan" onclick="changeShow(<?php echo $this->_var['child_0_13533800_1483523499']['id']; ?>,<?php echo $this->_var['tpl']; ?>)"><i class="icon"></i>换一组</a>
                </div>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>            
            </div>
            
			<div class="layout-r">
				<ul class="layout-cp">
				<?php $_from = $this->_var['one_cate_child']['goods_hot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
				<?php if ($this->_foreach['goods']['iteration'] > 1 && $this->_foreach['goods']['iteration'] < 5): ?>
					<li>
						<div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="120" height="120"></a></div>
						<div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" class="lh36" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
						<div class="p-price"><?php echo $this->_var['goods']['shop_price']; ?></div>
						<div class="original-price"><?php echo $this->_var['goods']['market_price']; ?></div>
						<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" class="btn2">去看看 &gt;</a>
					</li>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<div class="layout-brand">
					<ul>
					<?php $_from = $this->_var['one_cate_child']['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('kid', 'brand');$this->_foreach['brand'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brand']['total'] > 0):
    foreach ($_from AS $this->_var['kid'] => $this->_var['brand']):
        $this->_foreach['brand']['iteration']++;
?>
					<?php if (($this->_foreach['brand']['iteration'] - 1) < 8): ?>
						<li<?php if ($this->_foreach['brand']['iteration'] % 4 == 0): ?> class="last"<?php endif; ?>><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" height="35"/></a></li>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>