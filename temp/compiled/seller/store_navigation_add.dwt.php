<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>

<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<?php echo $this->fetch('library/url_here.lbi'); ?>
<div class="ecsc-layout">
    <div class="site wrapper">
    	<?php echo $this->fetch('library/seller_menu_left.lbi'); ?>
		<div class="ecsc-layout-right">
            <div class="main-content" id="mainContent">
            	<?php echo $this->fetch('library/seller_menu_tab.lbi'); ?>
                <div class="wrapper-list ecsc-form-default">
                <form action="merchants_navigator.php" method="post" name="form" onSubmit="return checkForm();">
                	<dl>
                    	<dt><?php echo $this->_var['lang']['system_main']; ?>：</dt>
                        <dd>
                        	<select onchange="add_main(this.value);" name="menulist" id="menulist" class="select">
                                <option value='-'>-</option>
                                <?php $_from = $this->_var['sysmain']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>
                                    <option value='<?php echo $this->_var['key']; ?>|<?php echo $this->_var['val']['cat_id']; ?>|<?php echo $this->_var['val']['cat_name']; ?>|<?php echo $this->_var['val']['url']; ?>' id="" url="<?php echo $this->_var['val']['url']; ?>"><?php echo $this->_var['val']['cat_name']; ?></option>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_name']; ?>：</dt>
                        <dd><input type="text" name="item_name" value="<?php echo $this->_var['rt']['item_name']; ?>" id="item_name" class="text" size="40" onKeyPress="javascript:key();" /></dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_url']; ?>：</dt>
                        <dd>
                        	<input type="text" name="item_url" value="<?php echo $this->_var['rt']['item_url']; ?>" id="item_url" class="text" size="40" onKeyPress="javascript:key();" />
                            <p class="fl" style="width:100%;"><label class="blue_label ml0"><?php echo $this->_var['lang']['notice_url']; ?></label></p>
                        </dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_catId']; ?>：</dt>
                        <dd><input type="text" id="item_catId" name="item_catId" value="<?php echo $this->_var['rt']['item_catId']; ?>" size="40" class="text text_2" /></dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_vieworder']; ?>：</dt>
                        <dd><input type="text" name="item_vieworder" value="<?php echo $this->_var['rt']['item_vieworder']; ?>" size="40" class="text text_2" /></dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_ifshow']; ?>：</dt>
                        <dd>
                        	<select name="item_ifshow" class="select">
                                <option value='1' <?php echo $this->_var['rt']['item_ifshow_1']; ?>><?php echo $this->_var['lang']['yes']; ?></option><option value='0' <?php echo $this->_var['rt']['item_ifshow_0']; ?>><?php echo $this->_var['lang']['no']; ?></option>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_opennew']; ?>：</dt>
                        <dd>
                        	<select name="item_opennew" class="select">
                                <option value='0' <?php echo $this->_var['rt']['item_opennew_0']; ?>><?php echo $this->_var['lang']['no']; ?></option>
                                <option value='1' <?php echo $this->_var['rt']['item_opennew_1']; ?>><?php echo $this->_var['lang']['yes']; ?></option>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                    	<dt><?php echo $this->_var['lang']['item_type']; ?>：</dt>
                        <dd>
                        	<select name="item_type" class="select">
                            	<option value='middle' <?php echo $this->_var['rt']['item_type_middle']; ?>><?php echo $this->_var['lang']['middle']; ?></option>
                            </select>
                        </dd>
                    </dl>
                    <div class="bottom">
                    	<div class="submit-border">
                    	<input type="hidden" name="id" value="<?php echo $this->_var['rt']['id']; ?>"/>
                        <input type="hidden" name="step" value="2"/>
                        <input type="hidden" name="act" value="<?php echo $this->_var['rt']['act']; ?>"/>
                        <input type="submit" class="button" name="Submit" value="<?php echo $this->_var['lang']['button_submit']; ?>"/>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript">
var last;
function add_main(val)
{
	val = val.split("|");
    
	$("#item_catId").val(val[1]);
	$("#item_name").val(val[2]);
	$("#item_url").val(val[3]);
}
function checkForm()
{
    if(document.getElementById('item_name').value == '')
    {
        alert('<?php echo $this->_var['lang']['namecannotnull']; ?>');
        return false;
    }
    if(document.getElementById('item_url').value == '')
    {
        alert('<?php echo $this->_var['lang']['linkcannotnull']; ?>');
        return false;
    }
    return true;
}

function key()
{
    last = document.getElementById('menulist').selectedIndex = 0;
}
<!--

onload = function()
{
  // 开始检查订单
  startCheckOrder();
}
//-->
</script>
</body>