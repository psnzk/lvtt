
<?php if ($this->_var['havealook']): ?>
<ul>
<?php $_from = $this->_var['havealook']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'look');if (count($_from)):
    foreach ($_from AS $this->_var['look']):
?>
<li class="ecsc-ps-item">
	<div class="ecsc-ps-photo"><a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank"><img src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/loadGoods.gif" width="184" height="184" class="lazy" data-original="<?php echo $this->_var['look']['thumb']; ?>"></a></div>
	<div class="ecsc-ps-c">
		<div class="p-name"><a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank"><?php echo $this->_var['look']['name']; ?></a></div>
		<div class="p-price">
			<?php if ($this->_var['goods']['promote_price'] != ''): ?>
			<?php echo $this->_var['look']['promote_price']; ?>
			<?php else: ?>
			<?php echo $this->_var['look']['shop_price']; ?>
			<?php endif; ?>
		</div>
		<a href="goods.php?id=<?php echo $this->_var['look']['id']; ?>" target="_blank" class="btn2">去看看 ></a>
	</div>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>