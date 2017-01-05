<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>
 
<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<div class="list-div" style="text-align:center;">
  <div class="fh_message">
  <div class="fr_content">
  	<div class="img">
    <?php if ($this->_var['msg_type'] == 0): ?>
    <i class="fh_icon information"></i>
    <?php elseif ($this->_var['msg_type'] == 1): ?>
    <i class="fh_icon warning"></i>
    <?php else: ?>
    <i class="fh_icon confirm"></i>
    <?php endif; ?>
  	</div>
  	<h3 class="<?php if ($this->_var['msg_type'] == 0): ?>information<?php elseif ($this->_var['msg_type'] == 1): ?>warning<?php else: ?>confirm<?php endif; ?>"><?php echo $this->_var['msg_detail']; ?></h3>
    <span class="ts" id="redirectionMsg"><?php if ($this->_var['auto_redirect']): ?><?php echo $this->_var['lang']['auto_redirection']; ?><?php endif; ?></span>
    <ul class="msg-link">
    <?php $_from = $this->_var['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
    <li><a href="<?php echo $this->_var['link']['href']; ?>" <?php if ($this->_var['link']['target']): ?>target="<?php echo $this->_var['link']['target']; ?>"<?php endif; ?>><?php echo $this->_var['link']['text']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
  </div>
  </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<?php if ($this->_var['auto_redirect']): ?>
<script type="text/javascript">
<!--
var seconds = 3;
var defaultUrl = "<?php echo $this->_var['default_url']; ?>";


onload = function()
{
  if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
  {
    document.getElementById('redirectionMsg').innerHTML = '';
    return;
  }

  window.setInterval(redirection, 1000);
}
function redirection()
{
  if (seconds <= 0)
  {
    window.clearInterval();
    return;
  }

  seconds --;
  document.getElementById('spanSeconds').innerHTML = seconds;

  if (seconds == 0)
  {
    //window.clearInterval(); //ie11不支持此写法
    location.href = defaultUrl;
  }
}
//-->

var height = $(window).height();
var header = $("header").height();
var footer = $(".footer").outerHeight();
//var fr_content = $(".fr_content").outerHeight();
$(".fh_message").height(height-header-footer);
//$(".fr_content").css("margin-top",(height-header-footer-fr_content)/2);
$(window).resize(function(){
	height = $(window).height();
	header = $("header").height();
	footer = $(".footer").outerHeight();
	//fr_content = $(".fr_content").outerHeight();
	$(".fh_message").height(height-header-footer);
	//$(".fr_content").css("margin-top",(height-header-footer-fr_content)/2);
});
</script>

<?php endif; ?>
</body>
</html>