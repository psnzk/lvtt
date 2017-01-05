<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php echo $this->_var['lang']['cp_home']; ?><?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="shortcut icon" href="../favicon.ico" />
<link rel="icon" href="../animated_favicon.gif" type="image/gif" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="css/purebox.css" rel="stylesheet" type="text/css">
<link href="js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="../js/calendar/calendar.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/spectrum-master/spectrum.css">
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,../js/jquery.json.js,../js/transport_jquery.js,../js/utils.js,listtable.js,listtable_pb.js,perfect-scrollbar.min.js,dsc_admin2.0.js,jquery-ui/jquery-ui.min.js,jquery.cookie.js,../js/calendar/calendar.min.js,jquery.bgColorSelector.js,jquery.validation.min.js,common.js,../js/jquery.form.js,jquery.nyroModal.js')); ?>
<!--[if lt IE 9]>
	<script src="js/css3-mediaqueries.js"></script>
<![endif]-->
<script language="JavaScript">
/*这里把JS用到的所有语言都赋值到这里*/
<?php $_from = $this->_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</script>