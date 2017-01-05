

<?php if ($this->_var['tpl'] == 2): ?>
<?php if ($this->_var['goods_list']): ?>
<ul>
	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
	<li>
	<div class="product-desc">
	<div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="135" height="135"></a></div>
	<div class="p-name" style="width: 176px; height: 36px; line-height:22px; padding-left: 5px; padding-right: 0px; overflow:hidden;" ><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
	<div class="ecsc-bp"><div class="p-price"><?php echo $this->_var['goods']['shop_price']; ?></div></div>
	</div>
	</li>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>
<?php endif; ?>


<?php if ($this->_var['tpl'] == 3): ?>
<?php if ($this->_var['goods_list']): ?>
<ul>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
	<li>
		<div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['thumb']; ?>" width="142" height="142"></a></div>
		<div class="p-name" style="height:36px;overflow:hidden;"><a href="#" target="_blank"><?php echo $this->_var['goods']['name']; ?></a></div>
		<div class="p-price" style="height: 21px;" >
		<?php if ($this->_var['goods']['promote_price'] != 0 && $this->_var['goods']['promote_price'] != ''): ?>
		<?php echo $this->_var['goods']['promote_price']; ?>
		<?php else: ?>
		<?php echo $this->_var['goods']['shop_price']; ?>
		<?php endif; ?>
		</div>
	</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>
<?php endif; ?>