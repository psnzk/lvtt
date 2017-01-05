
<?php if ($this->_var['ad_child']): ?>
<ul>
<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad_0_75074900_1483523913');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad_0_75074900_1483523913']):
        $this->_foreach['noad']['iteration']++;
?>
<li class="brand-item<?php if (($this->_foreach['noad']['iteration'] == $this->_foreach['noad']['total'])): ?> last<?php endif; ?>" style="background:<?php echo $this->_var['ad_0_75074900_1483523913']['link_color']; ?>;">
	<div class="brand-photo"><a href="<?php echo $this->_var['ad_0_75074900_1483523913']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['ad_0_75074900_1483523913']['ad_code']; ?>" width="<?php echo $this->_var['ad_0_75074900_1483523913']['ad_width']; ?>" height="<?php echo $this->_var['ad_0_75074900_1483523913']['ad_height']; ?>"></a></div>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>
