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
<link rel="stylesheet" type="text/css" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/quickLinks.css" />
<link rel="stylesheet" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/purebox.css">

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js')); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,global.js,utils.js,compare.js,search_category_menu.js,cart_common.js,cart_quick_links.js')); ?>

<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/sc_common.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.tabso_yeso.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.yomi.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/rotate3di.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/scroll_city.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/notLogin.js"></script>
</head>
<body>

<?php echo $this->fetch('library/page_header.lbi'); ?>

<div class="w1200">
    <div class="nch-breadcrumb-layout">
        <div class="nch-breadcrumb w1200" id="ur_here">
            <?php echo $this->fetch('library/ur_here.lbi'); ?>
        </div>
    </div> 
</div>

<div class="nch-container wrapper">
	<div class="left">
    	<?php echo $this->fetch('library/left_help.lbi'); ?>
    </div>
    <div class="right">
    	<div class="nch-article-con">
        	<div class="title">
                <h1><?php echo htmlspecialchars($this->_var['article']['title']); ?></h1>
                <h2><?php echo $this->_var['lang']['article_author']; ?>：<?php echo $this->_var['article']['author']; ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['lang']['time']; ?>：<?php echo $this->_var['article']['add_time']; ?></h2>
            </div>
            <div class="default">
            	<?php if ($this->_var['article']['content']): ?>
          		<?php echo $this->_var['article']['content']; ?>
         		<?php endif; ?>
                <?php if ($this->_var['article']['open_type'] == 2 || $this->_var['article']['open_type'] == 1): ?><br />
         		<div><a href="<?php echo $this->_var['article']['file_url']; ?>" target="_blank"><?php echo $this->_var['lang']['relative_file']; ?></a></div>
          		<?php endif; ?>
            </div>
            <div class="more_article">
            	<span class="art_prev">
                	<?php if ($this->_var['prev_article']): ?>
            		<?php echo $this->_var['lang']['prev_article']; ?>：<a href="<?php echo $this->_var['prev_article']['url']; ?>" ><?php echo $this->_var['prev_article']['title']; ?></a>
          			<?php endif; ?>
                </span>
                <br />
                <span class="art_next">
                	<?php if ($this->_var['next_article']): ?>
            		<?php echo $this->_var['lang']['next_article']; ?>：<a href="<?php echo $this->_var['next_article']['url']; ?>" ><?php echo $this->_var['next_article']['title']; ?></a>
          			<?php endif; ?>
                	
                </span>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<?php 
$k = array (
  'name' => 'user_menu_position',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.purebox.js"></script>
</body>
</html>
