<!--[if lte IE 9]>
<script> 
   (function() {
     if (! 
     /*@cc_on!@*/
     0) return;
     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
     var i= e.length;
     while (i--){
         document.createElement(e[i])
     } 
})() 
</script>
<![endif]-->
<header class="ecsc-head-layout w">
    <div class="wrapper">
        <div class="admin-logo">
            <a href="./" class="logo"><img src="images/logo.png" /></a>
            <h1>商家中心</h1>
        </div>
        <div class="index-search-container"></div>
        <div class="ecsc-admin">
            <span>您好！<strong style="color: red;"><?php echo $_SESSION['seller_name']; ?></strong> 欢迎您来到商家管理系统 </span>            
            <a href="../merchants_store.php?merchant_id=<?php echo $this->_var['ru_id']; ?>" target="_blank">[查看店铺]</a>
	    <!--<a href="visual_editing.php?act=first" target="_blank">[店铺装修]</a>-->
            <a href="privilege.php?act=modif">[个人设置]</a>
			<a href="index.php?act=clear_cache">[清除缓存]</a>
			<a href="privilege.php?act=logout">[退出]</a>
                        
            
        </div>
    </div>
    <nav class="ecsc-nav">
        <ul class="ecsc-nav-ul">
        <li ectype="item" <?php if (! $this->_var['menu_select']['action']): ?>class="current"<?php endif; ?>><a href="./">首页</a></li>
        <?php $_from = $this->_var['seller_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu');if (count($_from)):
    foreach ($_from AS $this->_var['menu']):
?>
        <?php if ($this->_var['menu']['url']): ?>
        <li ectype="item" class="<?php if ($this->_var['menu']['action'] == $this->_var['menu_select']['action']): ?>current <?php endif; ?><?php if ($this->_var['menu']['action'] == '19_merchants_store' || $this->_var['menu']['action'] == '18_batch_manage' || $this->_var['menu']['action'] == '02_cat_and_goods'): ?>w4<?php endif; ?><?php if ($this->_var['menu']['action'] == '03_promotion' || $this->_var['menu']['action'] == '10_priv_admin'): ?>w3<?php endif; ?><?php if ($this->_var['menu']['action'] == '05_banner' || $this->_var['menu']['action'] == '08_members' || $this->_var['menu']['action'] == '11_system'): ?>w2<?php endif; ?>">
        	<a href="<?php echo $this->_var['menu']['url']; ?>"><?php echo $this->_var['menu']['label']; ?></a>
            <ul>
                <?php $_from = $this->_var['menu']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
                <li><a href="<?php echo $this->_var['child']['url']; ?>" <?php if ($this->_var['child']['label'] == '店铺可视化装修'): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['child']['label']; ?></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </li>	
        <?php endif; ?>
       	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <div class="nav-current"></div>
    </nav>
</header>