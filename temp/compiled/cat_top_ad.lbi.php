
<?php if ($this->_var['ad_child']): ?>
<ul>
<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['noad']['iteration']++;
?>
  <li style="background:<?php echo $this->_var['ad']['link_color']; ?>;"><div class="banner-width"><a href="<?php echo $this->_var['ad']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['ad_code']; ?>" width="<?php echo $this->_var['ad']['ad_width']; ?>" height="<?php echo $this->_var['ad']['ad_height']; ?>" /></a></div></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>