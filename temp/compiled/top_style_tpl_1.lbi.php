<div class="nav ecsc-woman">
    <div class="w1200">
        <div class="categorys channel woman">
            <div class="dt"><?php if ($this->_var['cate_info']['cat_icon']): ?><i class="cat_icon"><img src="<?php echo $this->_var['cate_info']['cat_icon']; ?>" alt="图标" /></i><?php else: ?><i class="icon woman-icon"></i><?php endif; ?><?php echo $this->_var['cate_info']['cat_name']; ?></div>
            <div class="dd">
            <div class="cata-nav" id="parent-cata-nav">
                <?php $_from = $this->_var['categories_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
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
                                <?php if ($this->_foreach['no']['iteration'] < 6): ?>
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
        <div class="navitems" id="nav">
            <ul>
                <li><a href="index.php" <?php if ($this->_var['navigator_list']['config']['index'] == 1): ?>class="selected"<?php endif; ?>>首页</a></li>
                <?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_52738500_1483523795');$this->_foreach['nav_middle_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_middle_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav_0_52738500_1483523795']):
        $this->_foreach['nav_middle_list']['iteration']++;
?>
                <li><a href="<?php echo $this->_var['nav_0_52738500_1483523795']['url']; ?>" <?php if ($this->_var['nav_0_52738500_1483523795']['active'] == 1): ?>class="selected"<?php endif; ?> <?php if ($this->_var['nav_0_52738500_1483523795']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_0_52738500_1483523795']['name']; ?></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
    </div>
</div>
<div class="banner woman">
    <div class="classify-banner">
        <div class="bd" data-adposname="顶级分类页（女装模板）banner广告" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
        <?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['cat_top_ad'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        </div>
        <div class="ecsc-warp">
            <div class="hd"><ul></ul></div>
            <div class="banner-switch">
                <div class="banner-grab">
                    <div class="grab-l" data-adposname="顶级分类页（女装模板）今日抢购日期" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>>
                    <?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['cat_top_prom_ad'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
                    </div>
                    <div class="grab-r">
                        <div class="grab-pc">
                            <ul>
                            <?php $_from = $this->_var['cate_top_promote_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['promote'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['promote']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['promote']['iteration']++;
?>
                                <li>
                                    <div class="grab-con">
                                        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="135" height="135"/></a></div>
                                        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
                                        <div class="p-price">
                                        <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                             <?php echo $this->_var['goods']['promote_price']; ?>
                                        <?php else: ?>
                                             <?php echo $this->_var['goods']['shop_price']; ?>
                                        <?php endif; ?>
                                        </div>
                                        <a href="<?php echo $this->_var['goods']['url']; ?>" class="btn4" target="_blank">抢 ></a>
                                        <i class="icon dis"></i>
                                    </div>
                                </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content">
    <div class="lazy-ecsc-warp woman">
        <?php if ($this->_var['cate_top_new_goods']): ?>
        <div class="w1200">
            <div class="ecsc-new woman w932">
                <div class="ec-title"><h3>新品首发</h3></div>
                <div class="ec-content">
                    <div class="ecsc-tp">
                        <div class="ecsc-tp-photo" data-adposname="顶级分类页（女装模板）新品首发" <?php if ($this->_var['ad_reminder'] == 1): ?>ecdscType="adPos"<?php endif; ?>><?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['cat_top_new_ad'],
  'id' => $this->_var['cate_info']['cat_id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?></div>
                        <div class="ecsc-cp-r">
                            <ul>
                            <?php $_from = $this->_var['cate_top_new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['new'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['new']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['new']['iteration']++;
?>
                            <?php if ($this->_foreach['new']['iteration'] < 5): ?>
                                <li>
                                    <div class="product-desc">
                                        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="136" height="136"></a></div>
                                        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
                                        <div class="ecsc-bp">
                                            <div class="p-price">
                                            <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                                 <?php echo $this->_var['goods']['promote_price']; ?>
                                            <?php else: ?>
                                                 <?php echo $this->_var['goods']['shop_price']; ?>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                             <?php endif; ?>
                             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <ul>
                            <?php $_from = $this->_var['cate_top_new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['new'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['new']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['new']['iteration']++;
?>
                            <?php if ($this->_foreach['new']['iteration'] > 4 && $this->_foreach['new']['iteration'] < 9): ?>
                                <li>
                                    <div class="product-desc">
                                        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="136" height="136"></a></div>
                                        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
                                        <div class="ecsc-bp">
                                            <div class="p-price">
                                            <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                                 <?php echo $this->_var['goods']['promote_price']; ?>
                                            <?php else: ?>
                                                 <?php echo $this->_var['goods']['shop_price']; ?>
                                            <?php endif; ?>
                                            </div>  
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="ecsc-tp last">
                        <div class="ecsc-cp-r">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="ecsc-sales w240">
                <div class="ec-title"><h3>热销商品</h3></div>
                <div class="ec-content">
                    <ul class="ecsc-sales-list">
                    <?php $_from = $this->_var['cate_top_hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['hot'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hot']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['hot']['iteration']++;
?>
                    <li>
                        <b class="sales-num sales-num-<?php echo $this->_foreach['hot']['iteration']; ?>" id="<?php echo $this->_foreach['hot']['iteration']; ?>"><?php echo $this->_foreach['hot']['iteration']; ?></b>
                        <div class="sales-product-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="62" height="62"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['name']); ?></a></div>
                        <div class="p-price">
                        <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                             <?php echo $this->_var['goods']['promote_price']; ?>
                        <?php else: ?>
                             <?php echo $this->_var['goods']['shop_price']; ?>
                        <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div id="cat_top_lit"></div>
        <div class="w1200 floor" id="floor_loading" style="padding:120px 0px;">
            <div style="width:189px; height:150px; margin:auto;"><img src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/loading.gif"></div>
        </div>
    </div>
</div>
<input name="region_id" value="<?php echo $this->_var['region_id']; ?>" type="hidden">
<input name="area_id" value="<?php echo $this->_var['area_id']; ?>" type="hidden">
<input name="cat_id" value="<?php echo $this->_var['cate_info']['cat_id']; ?>" type="hidden">

<script type="text/javascript">
	//banner广告轮播调用
    $(".classify-banner").slide({titCell:".hd ul",mainCell:".bd ul",effect:"fold",pnLoop:true,autoPlay:true,autoPage:true});
	
	//异步加载每个楼层需加载的js
	function loadCategoryTop(key){
		var Floor = $("#floor_"+key);
		
		if(Floor.find(".floor-left-banner .bd li").length>1){
			Floor.find(".floor-left-banner").slide({titCell:".hd ul",mainCell:".bd ul",effect:"leftLoop",autoPlay:true,autoPage:true,delayTime:300,scroll:1,vis:1});
		}
		//顶级分类页广告栏按钮自适应宽度
        $.liWidth(".floor-left-banner");
		
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