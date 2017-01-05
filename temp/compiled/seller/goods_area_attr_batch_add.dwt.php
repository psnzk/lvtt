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
				<div class="info_warp">
				<form action="goods_area_attr_batch.php?act=upload" method="post" enctype="multipart/form-data" name="theForm" onsubmit="return formValidate()">
				<table width="100%" class="table_item">
				  <tr>
					<td colspan="2" class="pb20">
						<div class="alert-info"><?php echo $this->_var['lang']['use_help']; ?></div>
					</td>
				  </tr>
				  <?php if ($this->_var['goods_name']): ?>
				  <tr>
					<td class="label"><?php echo $this->_var['lang']['goods_name']; ?></td>
					<td class="red"><?php echo $this->_var['goods_name']; ?></td>
				  </tr>
				  <?php endif; ?>
				  <tr>
					<td class="label"><?php echo $this->_var['lang']['file_charset']; ?></td>
					<td>
					<select name="charset" id="charset" class="select">
					  <?php echo $this->html_options(array('options'=>$this->_var['lang_list'])); ?>
					</select>
					</td>
				  </tr>
				  <tr>
					<td class="label file_label pt5"><?php echo $this->_var['lang']['csv_file']; ?></td>
					<td class="pt5">
						<input name="file" type="file" size="40" class="file mt5" />
						<p id="noticeFile" class="red fl bf100"><?php echo $this->_var['lang']['notice_file']; ?></p>
					</td>
				  </tr>
				  <?php $_from = $this->_var['download_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('charset', 'download');if (count($_from)):
    foreach ($_from AS $this->_var['charset'] => $this->_var['download']):
?>
				  <?php if ($this->_var['charset'] == 'zh_cn'): ?>
				  <tr>
					<td>&nbsp;</td>
					<td><a href="goods_area_attr_batch.php?act=download&charset=<?php echo $this->_var['charset']; ?>&goods_id=<?php echo $this->_var['goods_id']; ?>&attr_name=<?php echo $this->_var['attr_name']; ?>" class="blue"><?php echo $this->_var['download']; ?></a></td>
				  </tr>
				  <?php endif; ?>
				  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				  <tr class="no-line">
					<td>&nbsp;</td>
					<td class="pt10 pb20"><input name="submit" type="submit" id="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" /></td>
				  </tr>
				</table>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>

<script language="JavaScript">
    var elements;
    onload = function()
    {
        // 文档元素对象
        elements = document.forms['theForm'].elements;

        // 开始检查订单
        startCheckOrder();
    }

    /**
     * 检查是否底级分类
     */
    function checkIsLeaf(selObj)
    {
        if (selObj.options[selObj.options.selectedIndex].className != 'leafCat')
        {
            alert(goods_cat_not_leaf);
            selObj.options.selectedIndex = 0;
        }
    }

    /**
     * 检查输入是否完整
     */
    function formValidate()
    {
        if (elements['cat'].value <= 0)
        {
            alert(please_select_cat);
            return false;
        }
        if (elements['file'].value == '')
        {
            alert(please_upload_file);
            return false;
        }
        return true;
    }
</script>

</body>
</html>