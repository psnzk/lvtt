
<?php if ($this->_var['ad_child']): ?>
<ul>
<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad_0_95562500_1483523796');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad_0_95562500_1483523796']):
        $this->_foreach['noad']['iteration']++;
?>
  <li<?php if (($this->_foreach['noad']['iteration'] == $this->_foreach['noad']['total'])): ?> class="last"<?php endif; ?>><a href="<?php echo $this->_var['ad_0_95562500_1483523796']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['ad_0_95562500_1483523796']['ad_code']; ?>" style="max-width:<?php echo $this->_var['ad_0_95562500_1483523796']['ad_width']; ?>px; max-height:<?php echo $this->_var['ad_0_95562500_1483523796']['ad_height']; ?>px;"/></a></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>