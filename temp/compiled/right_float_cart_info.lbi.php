<div class="tbar-panel-main">
    <div class="ibar_plugin_content">
    	<div class="tbar-panel-content">
        <div class="ibar_cart_group ibar_cart_product">
        <?php if ($this->_var['cart_info']['goods_list_count'] > 0): ?>
            <ul>
                <?php $_from = $this->_var['cart_info']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['good'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['good']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['good']['iteration']++;
?>
                <li class="cart_item <?php if (($this->_foreach['good']['iteration'] == $this->_foreach['good']['total'])): ?>last<?php endif; ?>">
                    <div class="cart_item_pic">
                        <a href="<?php echo $this->_var['goods']['url']; ?>"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></a>
                    </div>
                    <div class="cart_item_desc">
                    <a class="cart_item_name" href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a>
                    <div class="cart_item_price"><span class="cart_price"><?php echo $this->_var['goods']['goods_price']; ?></span>×<?php echo $this->_var['goods']['goods_number']; ?></div>
                    <div class="delete_cart"><a href="javascript:deleteCartGoods(<?php echo $this->_var['goods']['rec_id']; ?>,1);">删除</a></div>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        <?php else: ?>
        	<div class="ecs-tbar-tipbox2">
            	<div class="tip-inner">
                	<b class="b-face-fd"></b>
                    <div class="tip-text">购物车空空的，赶快去挑选心仪的商品吧~<br><a href="./index.php">去首页看看</a></div>
                </div>
            </div>
        <?php endif; ?>
        </div>
        </div>
        <div class="cart_handler">
            <div class="cart_handler_header">
                <span class="cart_handler_left">共<span class="cart_price"><?php echo $this->_var['cart_info']['number']; ?></span>件商品</span>
                <span class="cart_handler_right"><?php echo $this->_var['cart_info']['amount']; ?></span>
            </div>
            <a target="_blank" class="cart_go_btn" href="flow.php">去购物车结算</a>
        </div>
    </div>
</div>
<script>
	var winHeight =$(window).height();
	$(".tbar-panel-content").css({"height":winHeight-128});
	$(".tbar-panel-main").css({"height":winHeight-38});
	$(window).resize(function(){
		winHeight = $(this).height();
		$(".tbar-panel-main").css({"height":winHeight-38});
		$(".tbar-panel-content").css({"height":winHeight-128});
	});
</script>
