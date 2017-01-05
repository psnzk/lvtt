<?php if ($this->_var['full_page']): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>

<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<?php echo $this->fetch('library/url_here.lbi'); ?>
<div class="ecsc-layout">
    <div class="site wrapper">
		<?php echo $this->fetch('library/seller_menu_left.lbi'); ?>
		<div class="ecsc-layout-right">
            <div class="main-content" id="mainContent">
				<?php echo $this->fetch('library/seller_menu_tab.lbi'); ?>
				<div class="search-form">
                	<div class="fr">
                        <form action="javascript:searchAd_position()" name="searchForm">
                            <label><?php echo $this->_var['lang']['keyword']; ?>:</label>
                            <input type="text" name="keyword" size="15" class="text text_2 mr10" />
                            <div class="submit-border"><input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" /></div>
                        </form>
                    </div>
				</div>
				<?php endif; ?>
				<form method="post" action="" name="listForm">
					<!-- start ad position list -->
					<div class="list-div" id="listDiv"> 
						<table class="ecsc-default-table ecsc-table-seller mt20">
						  <tr>
							<th width="20%"><?php echo $this->_var['lang']['position_name']; ?></th>
							<th width="10%"><?php echo $this->_var['lang']['posit_width']; ?></th>
							<th width="10%"><?php echo $this->_var['lang']['posit_height']; ?></th>
							<th width="20%"><?php echo $this->_var['lang']['position_model']; ?></th>
							<th width="30%"><?php echo $this->_var['lang']['position_desc']; ?></th>
							<th width="10%"><?php echo $this->_var['lang']['handler']; ?></th>
						  </tr>
						  <?php $_from = $this->_var['position_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
						  <tr>
							<td class="first-cell">
								<span onclick="javascript:listTable.edit(this, 'edit_position_name', <?php echo $this->_var['list']['position_id']; ?>)"><?php echo htmlspecialchars($this->_var['list']['position_name']); ?></span>
							</td> 
							<td align="center"><span onclick="javascript:listTable.edit(this, 'edit_ad_width', <?php echo $this->_var['list']['position_id']; ?>)"><?php echo $this->_var['list']['ad_width']; ?></span></td>
							<td align="center"><span onclick="javascript:listTable.edit(this, 'edit_ad_height', <?php echo $this->_var['list']['position_id']; ?>)"><?php echo $this->_var['list']['ad_height']; ?></span></td>
							<td align="left"><span><?php echo $this->_var['list']['position_model']; ?></span></td>
							<td align="left"><span><?php echo htmlspecialchars($this->_var['list']['position_desc']); ?></span></td>
							<td align="center">
							  <a href="ads.php?act=list&pid=<?php echo $this->_var['list']['position_id']; ?>" title="<?php echo $this->_var['lang']['view']; ?><?php echo $this->_var['lang']['ad_content']; ?>" class="blue">查看广告</a>
						   <?php if ($this->_var['priv_ru'] == 0): ?>  
							  <?php if ($this->_var['list']['is_public'] != 1): ?>
							  |          
								  <a href="ad_position.php?act=edit&id=<?php echo $this->_var['list']['position_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="blue">编辑</a>
								  |
								  <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['position_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="blue">删除</a>
							  <?php endif; ?>
						   <?php else: ?>
							  |       
								<a href="ad_position.php?act=edit&id=<?php echo $this->_var['list']['position_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="blue">编辑</a>
								|
								<a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['position_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="blue">删除</a>
						   <?php endif; ?>       
							</td>
						  </tr>
						  <?php endforeach; else: ?>
							<tr><td class="no-records" colspan="7"><?php echo $this->_var['lang']['no_position']; ?></td></tr>
						  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</table>
						<?php if ($this->_var['full_page']): ?>
					</div>
					<!-- end ad_position list -->
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript">
  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  
  onload = function()
  {
    startCheckOrder();
  }
  
  <?php if ($this->_var['priv_ru'] == 1): ?>
	function get_store_search(val){
		if(val == 1){
			document.forms['searchForm'].elements['merchant_id'].style.display = '';
			document.forms['searchForm'].elements['store_keyword'].style.display = 'none';
			document.forms['searchForm'].elements['store_type'].style.display = 'none';
		}else if(val == 2){
			document.forms['searchForm'].elements['merchant_id'].style.display = 'none';
			document.forms['searchForm'].elements['store_keyword'].style.display = '';
			document.forms['searchForm'].elements['store_type'].style.display = 'none';
		}else if(val == 3){
			document.forms['searchForm'].elements['merchant_id'].style.display = 'none';
			document.forms['searchForm'].elements['store_keyword'].style.display = '';
			document.forms['searchForm'].elements['store_type'].style.display = '';
		}else{
			document.forms['searchForm'].elements['merchant_id'].style.display = 'none';
			document.forms['searchForm'].elements['store_keyword'].style.display = 'none';
			document.forms['searchForm'].elements['store_type'].style.display = 'none';
		}
	}
	<?php endif; ?>
  
  function searchAd_position()
    {
		<?php if ($this->_var['priv_ru'] == 1): ?>
		listTable.filter['store_search'] = Utils.trim(document.forms['searchForm'].elements['store_search'].value);
		listTable.filter['merchant_id'] = Utils.trim(document.forms['searchForm'].elements['merchant_id'].value);
		listTable.filter['store_keyword'] = Utils.trim(document.forms['searchForm'].elements['store_keyword'].value);
		listTable.filter['store_type'] = Utils.trim(document.forms['searchForm'].elements['store_type'].value);
		<?php endif; ?>
		
        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;

        listTable.loadList();
    }
  
</script>
</body>
</html>
<?php endif; ?>
