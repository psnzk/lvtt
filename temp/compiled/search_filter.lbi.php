<?php echo $this->smarty_insert_scripts(array('files'=>'warehouse.js')); ?>
<div id="filter">
    <div class="component-filter-sort mt0">
        <div class="filter-sortbar" id="filter-sortbar">
            <div class="button-strip">
                <a href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['pager']['search']['price_min']; ?>&price_max=<?php echo $this->_var['pager']['search']['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=goods_id&is_ship=<?php echo $this->_var['pager']['search']['is_ship']; ?>&order=<?php if ($this->_var['pager']['search']['sort'] == 'goods_id' && $this->_var['pager']['search']['order'] == 'DESC'): ?>ASC<?php else: ?>DESC<?php endif; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>" class="button-strip-item <?php if ($this->_var['pager']['search']['sort'] == 'goods_id'): ?>current<?php endif; ?>">默认<i class="icon <?php if ($this->_var['pager']['search']['sort'] == 'goods_id' && $this->_var['pager']['search']['order'] == 'DESC'): ?>icon-down<?php else: ?>icon-up<?php endif; ?>"></i></a>
                <a href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['pager']['search']['price_min']; ?>&price_max=<?php echo $this->_var['pager']['search']['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=sales_volume&is_ship=<?php echo $this->_var['pager']['search']['is_ship']; ?>&order=<?php if ($this->_var['pager']['search']['sort'] == 'sales_volume' && $this->_var['pager']['search']['order'] == 'DESC'): ?>ASC<?php else: ?>DESC<?php endif; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>" class="button-strip-item <?php if ($this->_var['pager']['search']['sort'] == 'sales_volume'): ?>current<?php endif; ?>">销量<i class="icon <?php if ($this->_var['pager']['search']['sort'] == 'sales_volume' && $this->_var['pager']['search']['order'] == 'DESC'): ?>icon-down<?php else: ?>icon-up<?php endif; ?>"></i></a>
                <a href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['pager']['search']['price_min']; ?>&price_max=<?php echo $this->_var['pager']['search']['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=last_update&is_ship=<?php echo $this->_var['pager']['search']['is_ship']; ?>&order=<?php if ($this->_var['pager']['search']['sort'] == 'last_update' && $this->_var['pager']['search']['order'] == 'DESC'): ?>ASC<?php else: ?>DESC<?php endif; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>" class="button-strip-item <?php if ($this->_var['pager']['search']['sort'] == 'last_update'): ?>current<?php endif; ?>">新品<i class="icon <?php if ($this->_var['pager']['search']['sort'] == 'last_update' && $this->_var['pager']['search']['order'] == 'DESC'): ?>icon-down<?php else: ?>icon-up<?php endif; ?>"></i></a>
                <a href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['pager']['search']['price_min']; ?>&price_max=<?php echo $this->_var['pager']['search']['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=comments_number&is_ship=<?php echo $this->_var['pager']['search']['is_ship']; ?>&order=<?php if ($this->_var['pager']['search']['sort'] == 'comments_number' && $this->_var['pager']['search']['order'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>#goods_list" class="button-strip-item <?php if ($this->_var['pager']['search']['sort'] == 'comments_number'): ?>current<?php endif; ?>">评论数<i class="icon <?php if ($this->_var['pager']['search']['sort'] == 'comments_number' && $this->_var['pager']['search']['order'] == 'DESC'): ?>icon-down<?php else: ?>icon-up<?php endif; ?>"></i></a>
                <a href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['pager']['search']['price_min']; ?>&price_max=<?php echo $this->_var['pager']['search']['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=shop_price&is_ship=<?php echo $this->_var['pager']['search']['is_ship']; ?>&order=<?php if ($this->_var['pager']['search']['sort'] == 'shop_price' && $this->_var['pager']['search']['order'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>" class="button-strip-item <?php if ($this->_var['pager']['search']['sort'] == 'shop_price'): ?>current<?php endif; ?>">价格<i class="icon <?php if ($this->_var['pager']['search']['sort'] == 'shop_price' && $this->_var['pager']['search']['order'] == 'DESC'): ?>icon-down<?php else: ?>icon-up<?php endif; ?>"></i></a>
            </div>
            <form method="GET" class="sort" name="listform" action="">
                <div class="price-button-strip">   
                    <div class="fP-box">
                    <input type="text" name="price_min" value="<?php if ($this->_var['pager']['search']['price_min']): ?><?php echo $this->_var['pager']['search']['price_min']; ?><?php endif; ?>" class="price-min" id="price-min" placeholder="￥<?php if ($this->_var['pager']['search']['price_min']): ?><?php echo $this->_var['pager']['search']['price_min']; ?><?php endif; ?>" />&nbsp;~&nbsp;<input type="text" name="price_max" class="price-max" id="price-max" value="<?php if ($this->_var['pager']['search']['price_max']): ?><?php echo $this->_var['pager']['search']['price_max']; ?><?php endif; ?>" placeholder="￥<?php if ($this->_var['pager']['search']['price_max']): ?><?php echo $this->_var['pager']['search']['price_max']; ?><?php endif; ?>" />
                    </div>
                    <div class="fP-expand">
                        <a class="ui-btn-s ui-btn-clear" href="search.php?keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['search']['sort']; ?>&order=<?php echo $this->_var['pager']['search']['order']; ?>&is_ship=<?php echo $this->_var['is_ship']; ?><?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>">清空</a>
                        <a href="javascript:void(0);" class="ui-btn-s ui-btn-s-primary ui-btn-submit">确定</a>
                    </div>
                </div>
                <input type="hidden" name="keywords" value="<?php echo $this->_var['pager']['search']['keywords']; ?>" />
                <input type="hidden" name="display" value="<?php echo $this->_var['pager']['display']; ?>" id="display" />
                <input type="hidden" name="page" value="<?php echo $this->_var['pager']['page']; ?>" />
                <input type="hidden" name="is_ship" value="<?php echo $this->_var['pager']['search']['is_ship']; ?>" />
                <input type="hidden" name="sort" value="<?php echo $this->_var['pager']['search']['sort']; ?>" />
                <input type="hidden" name="order" value="<?php echo $this->_var['pager']['search']['order']; ?>" />
            </form> 
            <?php if ($this->_var['open_area_goods']): ?>
            <div class="f-store dorpdown">
            <div class="sc-icon">
                <span class="fs-cell">收货地：</span>
                <div class="sc-choie"><span class="sc-address"><?php echo $this->_var['province_row']['region_name']; ?>&nbsp;<?php echo $this->_var['city_row']['region_name']; ?>&nbsp;<?php echo $this->_var['district_row']['region_name']; ?></span><i class="icon"></i></div>
            </div>
            <div class="dorpdown-layer" id="area_list">
                <div class="dd-spacer"></div>
                <div id="stock_list" class="stock_list">
                    <div class="mt">
                        <ul class="tab">
                            <li class="curr" onclick="region.selectArea(this, 1);" value="<?php echo $this->_var['province_row']['region_id']; ?>" id="province_li"><?php echo $this->_var['province_row']['region_name']; ?><i class="sc-icon-right"></i></li>
                            <li class="select_city" id="city_li" onclick="region.selectArea(this, 2);" value="<?php echo $this->_var['city_row']['region_id']; ?>"><?php echo $this->_var['city_row']['region_name']; ?><i class="sc-icon-right"></i></li>
                            <li class="select_district" id="district_type" onclick="region.selectArea(this, 3);" value="<?php echo $this->_var['city_district']['region_id']; ?>"><?php echo $this->_var['district_row']['region_name']; ?><i class="sc-icon-right"></i></li>
                        </ul>
                        <div class="stock-line"></div>
                    </div>
                    <div class="mc" id="house_list">
                        <ul class="area-list" id="province_list">
                         
                        <?php $_from = $this->_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'province_0_95949900_1483518331');$this->_foreach['noprovince'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noprovince']['total'] > 0):
    foreach ($_from AS $this->_var['province_0_95949900_1483518331']):
        $this->_foreach['noprovince']['iteration']++;
?>
                            <li>
                                <a v="<?php echo $this->_var['province_0_95949900_1483518331']['region_id']; ?>" title="<?php echo $this->_var['province_0_95949900_1483518331']['region_name']; ?>" onclick="region.getRegion(<?php echo $this->_var['province_0_95949900_1483518331']['region_id']; ?>, 2, city_list, this,<?php echo $this->_var['user_id']; ?>);" href="javascript:void(0);"><?php echo $this->_var['province_0_95949900_1483518331']['region_name']; ?></a>
                            </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        
                        </ul>
                    </div>
                    <div class="mc hide" id="city_list_id">
                        <ul class="area-list" id="city_list">
                                           	                             
                            <?php $_from = $this->_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'city');$this->_foreach['nocity'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nocity']['total'] > 0):
    foreach ($_from AS $this->_var['city']):
        $this->_foreach['nocity']['iteration']++;
?>                                     
                                <li>
                                    <a v="<?php echo $this->_var['city']['region_id']; ?>" title="<?php echo $this->_var['city']['region_name']; ?>" onclick="region.getRegion(<?php echo $this->_var['city']['region_id']; ?>, 3, district_list, '<?php echo $this->_var['city']['region_name']; ?>',<?php echo $this->_var['user_id']; ?>);" href="javascript:void(0);"><?php echo $this->_var['city']['region_name']; ?></a>  
                                </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        
                        </ul>
                    </div>
                    <div class="mc hide" id="district_list_id">
                        <ul class="area-list"  id="district_list">
                             
                            <?php $_from = $this->_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');$this->_foreach['nodistrict'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nodistrict']['total'] > 0):
    foreach ($_from AS $this->_var['district']):
        $this->_foreach['nodistrict']['iteration']++;
?>
                                <li>                     
                                    <a v="<?php echo $this->_var['county']['region_id']; ?>" title="<?php echo $this->_var['district']['region_name']; ?>" onclick="region.changedDis(<?php echo $this->_var['district']['region_id']; ?>,<?php echo $this->_var['user_id']; ?>);" href="javascript:void(0);" id="district_<?php echo $this->_var['district']['region_id']; ?>"><?php echo $this->_var['district']['region_name']; ?></a>  
                                </li>    
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        
                        </ul>
                    </div>
                    <p class="mod_storage_state">商品暂时只支持配送至中国大陆地区</p>
                    <div onclick="$('.f-store').removeClass('hover')" class="close"></div>
                    <input type="hidden" value="<?php echo $this->_var['province_row']['region_id']; ?>" id="province_id" name="province_region_id">
                     <input type="hidden" value="<?php echo $this->_var['city_row']['region_id']; ?>" id="city_id" name="city_region_id">
                     <input type="hidden" value="<?php if ($this->_var['district_row']['region_id']): ?><?php echo $this->_var['district_row']['region_id']; ?><?php else: ?>0<?php endif; ?>" id="district_id" name="district_region_id">         
                     <input type="hidden" value="<?php echo $this->_var['region_id']; ?>" id="region_id" name="region_id">
                     <input type="hidden" value="<?php echo $this->_var['goods_id']; ?>" id="good_id" name="good_id">
                     <input type="hidden" value="<?php echo $this->_var['user_id']; ?>" id="user_id" name="user_id">
                     <input type="hidden" value="<?php echo $this->_var['area_id']; ?>" id="area_id" name="area_id">
                     <input type="hidden" value="<?php echo $this->_var['goods']['user_id']; ?>" id="merchantId" name="merchantId">
                </div>
            </div>
        </div>
            <?php endif; ?>
            <div class="xz-button-strip">
                <div class="store-checkbox <?php if ($this->_var['pager']['search']['is_ship'] == 'is_shipping'): ?>checkbox-checked<?php endif; ?>">
                    <input type="checkbox" name="fk-type" id="store-checkbox-011" class="checkbox" <?php if ($this->_var['pager']['search']['is_ship'] == 'is_shipping'): ?>checked="checked"<?php endif; ?> />
                    <label for="store-checkbox-011">包邮</label>
                    <i id="input-i1" rev="search.php?<?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>&keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['price_min']; ?>&price_max=<?php echo $this->_var['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['search']['sort']; ?>&is_ship=is_shipping<?php if ($this->_var['pager']['search']['self_support'] == 1): ?>&is_self=1<?php endif; ?>"></i>
                    <i id="input-i2" rev="search.php?<?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>&keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['price_min']; ?>&price_max=<?php echo $this->_var['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['search']['sort']; ?>&order=<?php echo $this->_var['pager']['search']['order']; ?><?php if ($this->_var['pager']['search']['self_support'] == 1): ?>&is_self=1<?php endif; ?>"></i>
                </div>

                <div class="store-checkbox <?php if ($this->_var['pager']['search']['self_support'] == 1): ?>checkbox-checked<?php endif; ?>">
                    <input type="checkbox" name="fk-type" id="store-checkbox-012" class="checkbox" <?php if ($this->_var['pager']['search']['self_support'] == 1): ?>checked="checked"<?php endif; ?> />
                    <label for="store-checkbox-012">自营商品</label>
                    <i id="input-i1" rev="search.php?<?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>&keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['price_min']; ?>&price_max=<?php echo $this->_var['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['search']['sort']; ?>&is_self=1<?php if ($this->_var['pager']['search']['is_ship'] == 'is_shipping'): ?>&is_ship=is_shipping<?php endif; ?>"></i>
                    <i id="input-i2" rev="search.php?<?php if ($this->_var['cou_id']): ?>&cou_id=<?php echo $this->_var['cou_id']; ?><?php endif; ?>&keywords=<?php echo $this->_var['pager']['search']['keywords']; ?>&display=<?php echo $this->_var['pager']['display']; ?>&price_min=<?php echo $this->_var['price_min']; ?>&price_max=<?php echo $this->_var['price_max']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['search']['sort']; ?>&order=<?php echo $this->_var['pager']['search']['order']; ?><?php if ($this->_var['pager']['search']['is_ship'] == 'is_shipping'): ?>&is_ship=is_shipping<?php endif; ?>"></i>
                </div>

                <!--<div class="store-checkbox">
                    <input type="checkbox" name="fk-type" id="store-checkbox-012" class="checkbox">
                    <label for="store-checkbox-012">货到付款</label>
                </div>-->
            </div>
            <?php if (! $this->_var['category_load_type']): ?>
            <div class="button-page">
                <span class="pageState"><span><?php echo $this->_var['pager']['page']; ?></span>/<?php echo $this->_var['pager']['page_count']; ?></span>
                <?php if ($this->_var['pager']['page_next']): ?><a href="<?php echo $this->_var['pager']['page_next']; ?>" title="下一页" class="pageNext">&gt;</a><?php else: ?><a href="javascript:;">&gt;</a><?php endif; ?>
                <?php if ($this->_var['pager']['page_prev']): ?><a href="<?php echo $this->_var['pager']['page_prev']; ?>" title="上一页" class="pagePrev">&lt;</a><?php else: ?><a href="javascript:;">&lt;</a><?php endif; ?>
                
            </div>
            <?php endif; ?>
            <div class="styles">
                <ul class="items">
                    <li class="item current"><a href="javascript:void(0)" title="网格模式"><span class="icon icon-btn-switch-grid"></span></a></li>
                    <li class="item"><a href="javascript:void(0)" title="列表模式"><span class="icon icon-btn-switch-list"></span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//价格筛选提交
	$('.ui-btn-submit').click(function(){
		var min_price = Number($(".price-min").val());
		var max_price = Number($(".price-max").val());
		
		if(min_price == '' && max_price == ''){
			alert('请填写筛选价格');
			return false;
		}else if(min_price == ''){
			alert('请填写筛选左边价格');
			return false;
		}else if(max_price == ''){
			alert('请填写筛选右边价格');
			return false;
		}else if(min_price > max_price || min_price == max_price){
			alert('左边价格不能大于或等于右边价格');
			return false;
		}
		
		$("form[name='listform']").submit();
	});
	function selectStoreTab(a){
		var li =$(".tab").find("li").eq(a);
		if(!li.hasClass("curr")){
			li.addClass("curr").siblings().removeClass("curr");
		}
		$("#stock_list").find(".mc").eq(a).removeClass("hide").siblings(".mc").addClass("hide");
	}
</script>