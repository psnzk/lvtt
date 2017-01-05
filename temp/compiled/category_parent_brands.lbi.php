
<?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?>
    <a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" width="112" height="49" /></a>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>