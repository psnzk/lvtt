<div class="tabmenu">
	<ul class="tab pngFix">
		<?php $_from = $this->_var['tab_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu_0_41802600_1481119671');if (count($_from)):
    foreach ($_from AS $this->_var['menu_0_41802600_1481119671']):
?>
		<li <?php if ($this->_var['menu_0_41802600_1481119671']['curr']): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_var['menu_0_41802600_1481119671']['href']; ?>"><?php echo $this->_var['menu_0_41802600_1481119671']['text']; ?></a></li>
		<?php endforeach; else: ?>
		<li class="active"><a href="javascript:;"><?php echo $this->_var['ur_here']; ?></a></li>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>							
	</ul>
	<?php if ($this->_var['action_link']): ?>
	<a class="ecsc-btn ecsc-btn-ecblue" href="<?php echo $this->_var['action_link']['href']; ?>" id="actionSpan"><i class="icon-plus-sign"></i><?php echo $this->_var['action_link']['text']; ?></a>
	<?php endif; ?>
	<?php if ($this->_var['action_link2']): ?>
	<a class="ecsc-btn ecsc-btn-ecblue" href="<?php echo $this->_var['action_link2']['href']; ?>"><?php echo $this->_var['action_link2']['text']; ?></a>
	<?php endif; ?>
	<?php if ($this->_var['action_link3']): ?>
	<a class="ecsc-btn ecsc-btn-ecblue" href="<?php echo $this->_var['action_link3']['href']; ?>"><?php echo $this->_var['action_link3']['text']; ?></a>
	<?php endif; ?>
</div>