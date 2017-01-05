<div class="brand-top">
	<div class="letter">
		<ul>
			<li><a href="javascript:void(0);" data-letter="">全部品牌</a></li>
			<?php $_from = $this->_var['letter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'letter_0_80172000_1483499661');if (count($_from)):
    foreach ($_from AS $this->_var['letter_0_80172000_1483499661']):
?>
            <li><a href="javascript:void(0);" data-letter="<?php echo $this->_var['letter_0_80172000_1483499661']; ?>"><?php echo $this->_var['letter_0_80172000_1483499661']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<li><a href="javascript:void(0);" data-letter="QT">其他</a></li>
		</ul>
	</div>
	<div class="b_search">
		<input name="search_brand_keyword" id="search_brand_keyword" type="text" class="b_text" placeholder="品牌名称关键字查找" autocomplete="off" />
		<a href="javascript:void(0);" class="btn-mini"><i class="icon icon-search"></i></a>
	</div>
</div>
<div class="brand-list">
	<?php echo $this->fetch('library/search_brand_list.lbi'); ?>
</div>
<div class="brand-not" style="display:none;">没有符合"<strong>其他</strong>"条件的品牌</div>