
<?php if ($this->_var['custom_categories'] && $this->_var['cat_info']['cat_type'] == 1): ?>
<div class="nch-module nch-module-style01">
	<div class="title">
		<h3>商城资讯</h3>
	</div>
    <div class="content">
    	<ul class="nch-sidebar-article-class">
        	<?php $_from = $this->_var['custom_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'custom_cat');if (count($_from)):
    foreach ($_from AS $this->_var['custom_cat']):
?>
            <li>
            	<dl>
                	<dt class="nch_art_tit"><a href="<?php echo $this->_var['custom_cat']['url']; ?>"><?php echo $this->_var['custom_cat']['name']; ?></a><i class="icon"></i></dt>
                    <?php $_from = $this->_var['custom_cat']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'custom_child_cat');if (count($_from)):
    foreach ($_from AS $this->_var['custom_child_cat']):
?>
                    <dd><a href="<?php echo $this->_var['custom_child_cat']['url']; ?>"><?php echo $this->_var['custom_child_cat']['name']; ?></a></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
    
<?php if ($this->_var['sys_categories'] && $this->_var['cat_info']['cat_type'] > 1): ?>
<div class="nch-module nch-module-style01">
	<?php $_from = $this->_var['sys_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sys_cat');if (count($_from)):
    foreach ($_from AS $this->_var['sys_cat']):
?>
	<div class="title">
		<h3><?php echo $this->_var['sys_cat']['name']; ?></h3>
	</div>
    <div class="content">
    	<ul class="nch-sidebar-article-class">
        	<?php $_from = $this->_var['sys_cat']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'sys_child_cat');$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['sys_child_cat']):
        $this->_foreach['cat']['iteration']++;
?>
            <li>
            	<dl>
                	<dt class="nch_art_tit"><a href="<?php echo $this->_var['sys_child_cat']['url']; ?>"><?php echo $this->_var['sys_child_cat']['name']; ?></a><i class="icon"></i></dt>
                    <?php $_from = $this->_var['sys_child_cat']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sys_c_c_cat');if (count($_from)):
    foreach ($_from AS $this->_var['sys_c_c_cat']):
?>
                    <dd><a href="<?php echo $this->_var['sys_c_c_cat']['url']; ?>"><?php echo $this->_var['sys_c_c_cat']['name']; ?></a></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['new_article']): ?>
<div class="nch-module nch-module-style03">
	<div class="title">
		<h3>最新文章</h3>
	</div>
    <div class="content">
		<ul class="nch-sidebar-article-list">
        	<?php $_from = $this->_var['new_article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_0_72436100_1483499630');if (count($_from)):
    foreach ($_from AS $this->_var['article_0_72436100_1483499630']):
?>
            <li><a  href="<?php echo $this->_var['article_0_72436100_1483499630']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['article_0_72436100_1483499630']['title']); ?>" class="nch_art_tit"  style="background:none; border-bottom:1px dashed #ccc;"><?php echo $this->_var['article_0_72436100_1483499630']['title']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
	</div>
</div>
<?php endif; ?>
<script type="text/javascript">
	$(".nch_art_tit").click(function(){
		if($(this).parents("li").hasClass("hover")){
			$(this).parents("li").removeClass("hover");
		}else{
			$(this).parents("li").addClass("hover").siblings().removeClass("hover");
		}
	})
</script>

