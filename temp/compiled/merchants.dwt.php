<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>



<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/base.css" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS|<?php echo $this->_var['page_title']; ?>" href="<?php echo $this->_var['feed_url']; ?>" />
<link href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/merchants.css" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js,common.js,global.js,compare.js,search_category_menu.js')); ?>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/sc_common.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/rotate3di.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/scroll_city.js"></script>

</head>
<body>
<?php echo $this->fetch('library/page_header_narrow.lbi'); ?>

<div class="layoutcontainer">
    <div class="merIndex_top">
    	<div class="merIndex_small">
        	<?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['adarr'],
  'id' => $this->_var['marticle'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        	<div class="marBanner">
            	<a href="#"><img src="themes/ecmoban_dsc/images/merBanner.jpg" /></a>
            </div>
            <div class="help-home">
            	<a class="h-btn" href="<?php echo $this->_var['url_merchants_steps']; ?>">我要入驻</a>
                <a class="h-btn h-btn2" href="<?php echo $this->_var['url_merchants_steps_site']; ?>">入驻进度查询</a>
            </div>
        </div>
    </div>
    <div class="merContent">
    	<div class="left">
        	<div class="mt"><h2>商家入驻</h2></div>
            <div class="mc">
            	<dl>
                	<?php $_from = $this->_var['article_menu1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_0_06450900_1483499083');$this->_foreach['noarticle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noarticle']['total'] > 0):
    foreach ($_from AS $this->_var['article_0_06450900_1483499083']):
        $this->_foreach['noarticle']['iteration']++;
?>
                        <?php if ($this->_var['article_0_06450900_1483499083']['article_type'] == 1): ?>
                        <dt>><?php echo $this->_var['article_0_06450900_1483499083']['title']; ?></dt>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>    
                    <?php $_from = $this->_var['article_menu1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_0_06479400_1483499083');$this->_foreach['noarticle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noarticle']['total'] > 0):
    foreach ($_from AS $this->_var['article_0_06479400_1483499083']):
        $this->_foreach['noarticle']['iteration']++;
?>   
                        <?php if ($this->_var['article_0_06479400_1483499083']['article_type'] != 1): ?>
                        <dd <?php if ($this->_var['article_0_06479400_1483499083']['article_id'] == $this->_var['article_id']): ?>class="curr"<?php endif; ?>><a href="<?php echo $this->_var['article_0_06479400_1483499083']['url']; ?>"><?php echo $this->_var['article_0_06479400_1483499083']['title']; ?></a></dd>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
                <dl>
                	<?php $_from = $this->_var['article_menu2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_0_06518300_1483499083');$this->_foreach['noarticle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noarticle']['total'] > 0):
    foreach ($_from AS $this->_var['article_0_06518300_1483499083']):
        $this->_foreach['noarticle']['iteration']++;
?>
                        <?php if ($this->_var['article_0_06518300_1483499083']['article_type'] == 1): ?>
                        <dt>><?php echo $this->_var['article_0_06518300_1483499083']['title']; ?></dt>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>    
                    <?php $_from = $this->_var['article_menu2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_0_06545900_1483499083');$this->_foreach['noarticle'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noarticle']['total'] > 0):
    foreach ($_from AS $this->_var['article_0_06545900_1483499083']):
        $this->_foreach['noarticle']['iteration']++;
?>   
                        <?php if ($this->_var['article_0_06545900_1483499083']['article_type'] != 1): ?>
                        <dd <?php if ($this->_var['article_0_06545900_1483499083']['article_id'] == $this->_var['article_id']): ?>class="curr"<?php endif; ?>><a href="<?php echo $this->_var['article_0_06545900_1483499083']['url']; ?>"><?php echo $this->_var['article_0_06545900_1483499083']['title']; ?></a></dd>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
            </div>
        </div>
        <div class="right">
        	<div class="help-main"><?php echo $this->_var['article']['content']; ?></div>
        </div>
    </div>
</div>
    <div style="text-align:center;">
	
	<?php echo $this->fetch('library/ad_position.lbi'); ?>
    
    </div>
</div>

<?php echo $this->fetch('library/page_footer.lbi'); ?>

</body>
</html>
