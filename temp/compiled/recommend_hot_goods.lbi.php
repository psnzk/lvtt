
<?php if ($this->_var['hot_goods']): ?>
<div class="charts-item" style="display:none;">
    <ul>
    	<?php $_from = $this->_var['hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_89371700_1483523866');$this->_foreach['hot'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hot']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_89371700_1483523866']):
        $this->_foreach['hot']['iteration']++;
?>
        <li>
            <b class="sales-num sales-num-<?php if ($this->_foreach['hot']['iteration'] < 4): ?>1<?php else: ?>2<?php endif; ?>" id="<?php echo $this->_foreach['hot']['iteration']; ?>"><?php echo $this->_foreach['hot']['iteration']; ?></b>
            <div class="sales-product-img"><a href="<?php echo $this->_var['goods_0_89371700_1483523866']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods_0_89371700_1483523866']['name']); ?>"><img src="<?php echo $this->_var['goods_0_89371700_1483523866']['thumb']; ?>" width="52" height="52"></a></div>
            <div class="p-name"><a href="<?php echo $this->_var['goods_0_89371700_1483523866']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods_0_89371700_1483523866']['name']); ?>"><?php echo $this->_var['goods_0_89371700_1483523866']['short_style_name']; ?></a></div>
            <div class="p-price">
            	<?php if ($this->_var['goods_0_89371700_1483523866']['promote_price'] != ''): ?>
                    <?php echo $this->_var['goods_0_89371700_1483523866']['promote_price']; ?>
                <?php else: ?>
                    <?php echo $this->_var['goods_0_89371700_1483523866']['shop_price']; ?>
                <?php endif; ?> 
            </div>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>
<?php endif; ?> 