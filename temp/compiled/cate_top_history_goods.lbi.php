<?php if ($this->_var['history_goods']): ?>
<div class="floor w1200">
    <div class="ecsc-new w1200">
        <div class="ec-title"><h3>浏览记录</h3></div>
    </div>
    <div class="floor-misto">
        <div class="ecsc-cp-r">
            <div class="floor-warpedg">
            <?php $_from = $this->_var['history_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hi');if (count($_from)):
    foreach ($_from AS $this->_var['hi']):
?>
            <ul>
                 <?php $_from = $this->_var['hi']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_48154700_1483523495');if (count($_from)):
    foreach ($_from AS $this->_var['goods_0_48154700_1483523495']):
?>
                <li>
                    <div class="product-desc">
                        <div class="p-img"><a href="<?php echo $this->_var['goods_0_48154700_1483523495']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods_0_48154700_1483523495']['goods_thumb']; ?>" width="136" height="136"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['goods_0_48154700_1483523495']['url']; ?>" target="_blank"><?php echo $this->_var['goods_0_48154700_1483523495']['short_name']; ?></a></div>
                        <div class="ecsc-bp">
                            <div class="p-price">
                            <?php if ($this->_var['goods_0_48154700_1483523495']['promote_price'] != ''): ?>
                                <?php echo $this->_var['goods_0_48154700_1483523495']['promote_price']; ?>
                            <?php else: ?>
                                <?php echo $this->_var['goods_0_48154700_1483523495']['shop_price']; ?>
                            <?php endif; ?>
                            </div>
                            <div class="original-price"><?php echo $this->_var['goods_0_48154700_1483523495']['market_price']; ?></div>
                        </div>
                        <a href="<?php echo $this->_var['goods_0_48154700_1483523495']['url']; ?>" class="btn-cart">查看详情 ></a>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <a href="javascript:void(0);" class="banner-prev"></a>
            <a href="javascript:void(0);" class="banner-next"></a>
            <span class="pageState"></span>
        </div>
    </div>
</div>
<?php endif; ?>