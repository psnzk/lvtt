
<?php if ($this->_var['ad_child']): ?>
<div class="bd">
<ul>
<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad_0_30582700_1483523816');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad_0_30582700_1483523816']):
        $this->_foreach['noad']['iteration']++;
?>
<li class="hot-adv-position" style="background:<?php echo $this->_var['ad_0_30582700_1483523816']['link_color']; ?>;"><a href="<?php echo $this->_var['ad_0_30582700_1483523816']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['ad_0_30582700_1483523816']['ad_code']; ?>" width="<?php echo $this->_var['ad_0_30582700_1483523816']['ad_width']; ?>" height="<?php echo $this->_var['ad_0_30582700_1483523816']['ad_height']; ?>"/></a></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
</div>
<div class="hd"><ul></ul></div>
<?php endif; ?>
