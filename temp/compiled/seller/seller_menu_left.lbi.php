<div class="ecsc-layout-left">
	<div class="sidebar" id="sidebar">
		<div class="column-menu">
			<ul class="seller_center_left_menu">
				<?php $_from = $this->_var['seller_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'menu_0_41518100_1481119671');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['menu_0_41518100_1481119671']):
?>
					<?php if ($this->_var['menu_0_41518100_1481119671']['action'] == $this->_var['menu_select']['action']): ?>
						<?php $_from = $this->_var['menu_0_41518100_1481119671']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_41543000_1481119671');if (count($_from)):
    foreach ($_from AS $this->_var['child_0_41543000_1481119671']):
?>
							<li <?php if ($this->_var['menu_select']['current'] == $this->_var['child_0_41543000_1481119671']['action']): ?>class="current"<?php endif; ?>><a href="<?php echo $this->_var['child_0_41543000_1481119671']['url']; ?>" <?php if ($this->_var['child_0_41543000_1481119671']['label'] == '店铺可视化装修'): ?>target="_blank"<?php endif; ?>> <?php echo $this->_var['child_0_41543000_1481119671']['label']; ?> </a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	</div>
</div>