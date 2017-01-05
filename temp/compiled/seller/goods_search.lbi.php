<!--高级搜索 start-->
<form method="get" action="javascript:searchGoods()" name="searchForm">
<div class="gj_search">
	<div class="search-gao-list" id="searchBarOpen">
		<i class="icon icon-zoom-in"></i>高级搜索
	</div>
	<div class="search-gao-bar" style="right:-350px;">
		<div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i>收起边栏</div>
		<div class="title"><h3>高级搜索</h3></div>
			<div class="searchContent w300">
				<div class="layout-box">
                	<!-- 分类 -->
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
                    <!-- 分类end -->
                    
                    <!-- 推荐 -->
					<dl class="w140">
						<dt><?php echo $this->_var['lang']['act_rec']; ?></dt>
						<dd>
							<div id="" class="imitate_select select_w140">
								<div class="cite">请选择</div>
								<ul>
									<li><a href="javascript:;" data-value="" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
									<?php $_from = $this->_var['intro_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="intro_type" type="hidden" value="">
							</div>
						</dd>
					</dl>
                    <!-- 推荐 end-->
                    
                    <!-- 供货商 -->
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
								<input name="suppliers_id" type="hidden" value="0">
							</div>
						</dd>
					</dl>
					<?php endif; ?>
                    <!-- 供货商 end-->
                    
                    <!-- 上架 -->
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
                    <!-- 上架 end-->
					
                    <!-- 品牌 -->
                    <dl class="w140">
						<dt><?php echo $this->_var['lang']['brand']; ?></dt>
						<dd>
							<div id="selbrand" class="imitate_select select_w140 mr10">
								<div class="cite">请选择</div>
								<ul>
                                	<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['goods_brand']; ?></a></li>
                                	<?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['brand']):
?>
									<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['brand']; ?></a></li>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<input name="brand_id" type="hidden" value="0">
							</div>						
						</dd>
					</dl>
                    <!-- 品牌 -->
                    
                    <!-- 关键字 -->
                    <dl class="w140">
						<dt><?php echo $this->_var['lang']['keyword']; ?></dt>
						<dd>
							<input type="text" name="keyword" size="15" class="text w270 mr0" autocomplete="off" />						
						</dd>
					</dl>		
                    <!-- 关键字 end-->
                    		
				</div>
			</div>
			<div class="bot_btn">
				<input type="submit" class="btn red_btn" name="tj_search" value="提交查询" />
				<input type="reset" class="btn btn_reset" name="reset" value="重置" />
			</div>
	</div>
</div>
</form>
<!--高级搜索 end-->	


<script language="JavaScript">

$.gjSearch("-350px");
function searchGoods()
{
	<?php if ($_GET['act'] != "trash"): ?>
	listTable.filter['cat_id'] = document.forms['searchForm'].elements['cat_id'].value;
	
	listTable.filter['brand_id'] = document.forms['searchForm'].elements['brand_id'].value;
	listTable.filter['intro_type'] = document.forms['searchForm'].elements['intro_type'].value;
	  <?php if ($this->_var['suppliers_exists'] == 1): ?>
	  listTable.filter['suppliers_id'] = document.forms['searchForm'].elements['suppliers_id'].value;
	  <?php endif; ?>
	listTable.filter['is_on_sale'] = document.forms['searchForm'].elements['is_on_sale'].value;
	<?php endif; ?>

	listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
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
</script>
