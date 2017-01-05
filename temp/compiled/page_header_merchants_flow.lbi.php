<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/suggest.css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'suggest.js')); ?>

<div id="site-nav">
    <div class="w1200 dorpdown">
    	<?php 
$k = array (
  'name' => 'header_region',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        <div class="user-entry"></div>
        <div class="ecsc-login" id="ECS_MEMBERZONE">
        	<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        </div>
        <ul class="quick-menu fr">
        	<?php if ($this->_var['navigator_list']['top']): ?>
            <?php $_from = $this->_var['navigator_list']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'nav');$this->_foreach['nav_top_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_top_list']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['nav']):
        $this->_foreach['nav_top_list']['iteration']++;
?>
            <?php if (($this->_foreach['nav_top_list']['iteration'] - 1) < 4): ?>
            <li>
            	<div class="dt"><a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a></div>
            </li>
            <li class="spacer"></li>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
            <?php if ($this->_var['navigator_list']['top']): ?>
            <li class="li_dorpdown">
            	<div class="dt"><a href="javascript:;" >网站导航</a><i class="ci-right"><s>◇</s></i></div>
                <div class="dd dorpdown-layer">
                	<div class="dd-spacer"></div>
                    <?php $_from = $this->_var['navigator_list']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'nav');$this->_foreach['nav_top_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_top_list']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['nav']):
        $this->_foreach['nav_top_list']['iteration']++;
?>
            			<?php if (($this->_foreach['nav_top_list']['iteration'] - 1) >= 4): ?>
                    		<div class="item"><a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a></div>
                    	<?php endif; ?>
            		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            </li>
            <?php endif; ?>
        </ul>
        <div class="shopcart-2015" id="ECS_CARTINFO" data-carteveval="0">
        	<?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        </div>
    </div>
</div>
<div class="header w1200">
    <div class="ecsc-logo"><a href="<?php echo $this->_var['url_index']; ?>" class="logo"><img src="themes/ecmoban_dsc/images/logo.gif" /></a></div>
    <div class="ecsc-join"><a href="<?php echo $this->_var['url_merchants']; ?>" target="_blank"><img src="themes/ecmoban_dsc/images/ecsc-join.gif" /></a></div>
    <div class="ecsc-search">
        <form id="searchForm" name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()" class="ecsc-search-form">
            <div class="ecsc-search-tabs">
                <i class="sc-icon-right"></i>
                <ul class="shop_search" id="shop_search">
                <?php if ($this->_var['search_type'] == 1): ?>
                    <li rev="1"><span>店铺</span></li>
                    <li rev="0" class="curr"><span>商品</span></li>
                <?php else: ?>
                    <li rev="0"><span>商品</span></li>
                    <li rev="1" class="curr"><span>店铺</span></li>
                <?php endif; ?>   
                </ul>
            </div>
            <input autocomplete="off" onKeyUp="lookup(this.value);" name="keywords" type="text" id="keyword" value="<?php 
$k = array (
  'name' => 'rand_keyword',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>" class="ecsc-search-input"/>
            <input type="hidden" name="store_search_cmt" value="<?php echo empty($this->_var['search_type']) ? '0' : $this->_var['search_type']; ?>">
            <button type="submit" class="ecsc-search-button"><i></i></button>
        </form>
        <div class="keyword">
        <?php if ($this->_var['searchkeywords']): ?>
            <ul>
                <?php $_from = $this->_var['searchkeywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?> <li><a href="search.php?keywords=<?php echo urlencode($this->_var['val']); ?>" target="_blank"><?php echo $this->_var['val']; ?></a></li> <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php endif; ?>
        </div>
        
            <div class="suggestions_box" id="suggestions" style="display:none;">
                <div class="suggestions_list" id="auto_suggestions_list">
                &nbsp;
                </div>
            </div>
        
    </div>
    <div class="site-commitment">
        <div class="site-commitment-front"><a style="background:url(<?php echo $this->_var['site_commitment']; ?>) -6px 0px no-repeat;" href="#" target="_blank"></a></div>
        <div class="site-commitment-back"><a style="background:url(<?php echo $this->_var['site_commitment']; ?>) -6px 0px no-repeat;" href="#" target="_blank"></a></div>
    </div>
</div>