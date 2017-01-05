<!--高级搜索 start-->
<form action="javascript:searchGoods()" name="searchForm">
<div class="gj_search">
	<div class="search-gao-list" id="searchBarOpen">
		<i class="icon icon-zoom-in"></i>高级搜索
	</div>
	<div class="search-gao-bar" style="right:-350px;">
		<div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i>收起边栏</div>
		<div class="title"><h3>高级搜索</h3></div>
		<form method="get" name="formSearch" id="formSearch">
			<div class="searchContent w300">
				<div class="layout-box">
					<?php if ($_GET['act'] != "trash"): ?>
					<dl class="w300">
						<dt><?php echo $this->_var['lang']['category']; ?></dt>
						<dd>
                            <div class="categorySelect">
                                <div class="selection">
                                    <input type="text" name="category_name" id="category_name" class="text w260 mr0 valid" value="请选择分类" autocomplete="off" readonly data-filter="cat_name" />
                                    <input type="hidden" name="cat_id" id="cat_id" value="0" data-filter="cat_id" />
                                </div>
                                <div class="select-container" style="width:290px; display:none;">
                                    <?php echo $this->fetch('library/filter_category.lbi'); ?>
                                </div>
                            </div>
						</dd>
					</dl>
					<dl class="w140">
						<dt><?php echo $this->_var['lang']['act_rec']; ?></dt>
						<dd>
							<div id="" class="imitate_select select_w140">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
									<?php $_from = $this->_var['intro_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="intro_type" type="hidden" value="<?php echo empty($_GET['intro_type']) ? '0' : $_GET['intro_type']; ?>" id="">
							</div>
						</dd>
					</dl>
					<?php if ($this->_var['suppliers_exists'] == 1): ?>
					<dl class="w140">
						<dt><?php echo $this->_var['lang']['supplier']; ?></dt>
						<dd>
							<div id="" class="imitate_select select_w140">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
									<?php $_from = $this->_var['suppliers_list_name']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="suppliers_id" type="hidden" value="<?php echo empty($_GET['suppliers_id']) ? '0' : $_GET['suppliers_id']; ?>" id="">
							</div>
						</dd>
					</dl>
					<?php endif; ?>
					<dl class="w140">
						<dt><?php echo $this->_var['lang']['is_on_sale']; ?></dt>
						<dd>	
							<div id="" class="imitate_select select_w140">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
									<li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['on_sale']; ?></a></li>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['not_on_sale']; ?></a></li>
								</ul>
								<input name="is_on_sale" type="hidden" value="" id="">
							</div>								
						</dd>
					</dl>
					<?php endif; ?>
					<dl class="w140">
						<dt><?php echo $this->_var['lang']['audited']; ?></dt>
						<dd>
							<div id="" class="imitate_select select_w140">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
									<li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['not_audited']; ?></a></li>
									<li><a href="javascript:;" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['audited_not_adopt']; ?></a></li>
									<li><a href="javascript:;" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['audited_yes_adopt']; ?></a></li>
								</ul>
								<input name="review_status" type="hidden" value="0" id="">
							</div>
						</dd>
					</dl>
					<?php if ($this->_var['priv_ru'] == 1): ?>
					<dl class="w300">
						<dt><?php echo $this->_var['lang']['steps_shop_name']; ?></dt>
						<dd>
							<div id="" class="imitate_select select_w140 mr10">
								<div class="cite">请选择</div>
								<ul>
                                	<li><a href="javascript:get_store_search(0);" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
									<li><a href="javascript:get_store_search(4);" data-value="4" class="ftx-01"><?php echo $this->_var['lang']['platform_self']; ?></a></li>
									<li><a href="javascript:get_store_search(1);" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
									<li><a href="javascript:get_store_search(2);" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
									<li><a href="javascript:get_store_search(3);" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
								</ul>
								<input name="store_search" type="hidden" value="0" id="">
							</div>
							<div id="" class="imitate_select select_w140" style="display:none">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
									<?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['store']['ru_id']; ?>" class="ftx-01"><?php echo $this->_var['store']['store_name']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="merchant_id" type="hidden" value="0" id="">
							</div>
							<input name="store_keyword" type="text" style="display:none" class="text w120 mr0" autocomplete="off"/>	
							<div id="" class="imitate_select select_w140 mt10" style="display:none">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>" class="ftx-01"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
								</ul>
								<input name="store_type" type="hidden" value="0" id="">
							</div>
						</dd>
					</dl>
					<?php endif; ?>
					
                    <dl class="w300">
						<dt><?php echo $this->_var['lang']['brand']; ?></dt>
						<dd>
							<div id="selbrand" class="imitate_select select_w140 mr10">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:get_selbrand(0);" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['goods_brand']; ?></a></li>
									<li><a href="javascript:get_selbrand(1);" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['06_goods_brand_list']; ?></a></li>
									<li><a href="javascript:get_selbrand(2);" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['07_merchants_brand']; ?></a></li>
								</ul>
								<input name="sel_brand" type="hidden" value="0" id="">
							</div>
							<div id="sel_mode" class="imitate_select select_w140" style="display: none;">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:get_brand_type(0);" data-value="0" class="ftx-01">请选择</a></li>
									<li><a href="javascript:get_brand_type(1);" data-value="1" class="ftx-01">按关键字</a></li>
									<li><a href="javascript:get_brand_type(2);" data-value="2" class="ftx-01">按列表</a></li>
								</ul>
								<input name="sel_mode" type="hidden" value="0" id="">
							</div>
							<input type="text" name="brand_keyword" id="brand_keyword" style="display: none;" class="text w120 mt10" autocomplete="off" />
							<div id="brandId" class="imitate_select select_w140" <?php if ($this->_var['priv_ru'] == 1): ?>style="display:none"<?php endif; ?>>
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['goods_brand']; ?></a></li>
									<?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="brand_id" type="hidden" value="" id="">
							</div>								
							<?php if ($this->_var['priv_ru'] == 1): ?>
							<div id="storeBrand" class="imitate_select select_w140 mt10" style="display:none">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['goods_brand']; ?></a></li>
									<?php $_from = $this->_var['store_brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="store_brand" type="hidden" value="" id="">
							</div>								
							<?php endif; ?>							
						</dd>
					</dl>
                    <dl class="w140">
						<dt><?php echo $this->_var['lang']['keyword']; ?></dt>
						<dd>
							<input type="text" name="keyword" size="15" class="text w270 mr0" autocomplete="off" />						
						</dd>
					</dl>				
				</div>
			</div>
			<div class="bot_btn">
				<input type="submit" class="btn red_btn" name="tj_search" value="提交查询" />
				<input type="reset" class="btn btn_reset" name="reset" value="重置" />
			</div>
		</form>
	</div>
</div>
</form>
<!--高级搜索 end-->	


<script language="JavaScript">
	$.gjSearch("-350px");
	
	<?php if ($this->_var['priv_ru'] == 1): ?>
	function get_store_search(val){
		if(val == 1){
			$("input[name=merchant_id]").parent(".imitate_select").show();
			$("input[name=store_keyword]").hide();
			$("input[name=store_type]").parent(".imitate_select").hide();
		}else if(val == 2){
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").show();
			$("input[name=store_type]").parent(".imitate_select").hide();			
		}else if(val == 3){
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").show();
			$("input[name=store_type]").parent(".imitate_select").show();			
		}else{
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").hide();
			$("input[name=store_type]").parent(".imitate_select").hide();			
		}
	}
	<?php endif; ?>
	
	function searchGoods()
	{
		<?php if ($_GET['act'] != "trash"): ?>
		listTable.filter['cat_id'] = document.forms['searchForm'].elements['cat_id'].value;
		
		//传品牌归属值 ecmoban模板堂 --zhuo
		if(document.forms['searchForm'].elements['sel_brand']){
			listTable.filter['sel_brand'] = document.forms['searchForm'].elements['sel_brand'].value;
			listTable.filter['store_brand'] = document.forms['searchForm'].elements['store_brand'].value;
		}
		
		listTable.filter['brand_id'] = document.forms['searchForm'].elements['brand_id'].value;
		listTable.filter['review_status'] = document.forms['searchForm'].elements['review_status'].value;
		listTable.filter['intro_type'] = document.forms['searchForm'].elements['intro_type'].value;
		  <?php if ($this->_var['suppliers_exists'] == 1): ?>
		  listTable.filter['suppliers_id'] = document.forms['searchForm'].elements['suppliers_id'].value;
		  <?php endif; ?>
		listTable.filter['is_on_sale'] = document.forms['searchForm'].elements['is_on_sale'].value;
		<?php endif; ?>

		<?php if ($this->_var['priv_ru'] == 1): ?>
		listTable.filter['store_search'] = Utils.trim(document.forms['searchForm'].elements['store_search'].value);
		listTable.filter['merchant_id'] = Utils.trim(document.forms['searchForm'].elements['merchant_id'].value);
		listTable.filter['store_keyword'] = Utils.trim(document.forms['searchForm'].elements['store_keyword'].value);
		listTable.filter['store_type'] = Utils.trim(document.forms['searchForm'].elements['store_type'].value);
		<?php endif; ?>

		listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
		// 品牌搜索  -qin
		if(document.forms['searchForm'].elements['brand_keyword']){
			listTable.filter['brand_keyword'] = Utils.trim(document.forms['searchForm'].elements['brand_keyword'].value);
		}
		if(document.forms['searchForm'].elements['sel_mode']){
			listTable.filter['sel_mode'] = Utils.trim(document.forms['searchForm'].elements['sel_mode'].value);
		}

		listTable.filter['page'] = 1;

		listTable.loadList();
	}

// 显示品牌选择方式
function get_brand_type(val)
{
	//var selbrand = document.getElementById('selbrand').value;
	var selbrand = $("input[name=sel_brand]").val();
	var brandId = document.getElementById('brandId');
	var storeBrand = document.getElementById('storeBrand');
	var brand_keyword = document.getElementById('brand_keyword');

	var sel_mode = document.getElementById('sel_mode');
	if(val == 0)
	{
		brandId.style.display = 'none';
		storeBrand.style.display = 'none';
		brand_keyword.style.display = 'none';
	}
	else if(val == 1)
	{
		brand_keyword.style.display = '';
		brandId.style.display = 'none';
		storeBrand.style.display = 'none';
	}
	else if(val == 2)
	{
		if(selbrand == 1){
			brandId.style.display = '';
			storeBrand.style.display = 'none';
		}else if(selbrand == 2){
			brandId.style.display = 'none';
			storeBrand.style.display = '';
		}
		
		brand_keyword.style.display = 'none';
	}
}

//判断选择品牌归属 ecmoban模板堂 --zhuo
function get_selbrand(val){
	
	var sel_mode = document.getElementById('sel_mode');
	var brandId = document.getElementById('brandId');
	var storeBrand = document.getElementById('storeBrand');
	var brand_keyword = document.getElementById('brand_keyword');

	var selbrand = document.getElementById('selbrand').value;
	
	if(val == 1)
	{	
		sel_mode.style.display = '';
	}else if(val == 2){
		sel_mode.style.display = '';
	}else{
		sel_mode.style.display = 'none';
	}
	
	brand_keyword.style.display = 'none';
	brandId.style.display = 'none';
	storeBrand.style.display = 'none';
}
</script>
