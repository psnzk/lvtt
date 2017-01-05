
<?php if ($this->_var['ad_child']): ?>
<div class="brand-adv">
    <div class="bd">
        <ul>
        	<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad_0_80824100_1483580494');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad_0_80824100_1483580494']):
        $this->_foreach['noad']['iteration']++;
?>
            <li><a href="<?php echo $this->_var['ad_0_80824100_1483580494']['ad_link']; ?>" target="_blank"><img src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/loadBrandBanner.gif" style="max-width:<?php echo $this->_var['ad_0_80824100_1483580494']['ad_width']; ?>px; max-height:<?php echo $this->_var['ad_0_80824100_1483580494']['ad_width']; ?>px; display: inline;" class="lazy" data-original="<?php echo $this->_var['ad_0_80824100_1483580494']['ad_code']; ?>"></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <div class="hd"><ul></ul></div>
</div>
<?php endif; ?>