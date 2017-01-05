<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper shop_special">
    	<div class="title">系统设置 - <?php echo $this->_var['ur_here']; ?></div>
		<div class="content">
        	<div class="tabs_info">
            	<ul>
                    <?php $_from = $this->_var['group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'group');$this->_foreach['bar_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bar_group']['total'] > 0):
    foreach ($_from AS $this->_var['group']):
        $this->_foreach['bar_group']['iteration']++;
?>
                    <li class="<?php if (($this->_foreach['bar_group']['iteration'] <= 1)): ?>curr<?php endif; ?>"><a href="javascript:void(0);"><?php echo $this->_var['group']['name']; ?></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <div class="explanation" id="explanation">
                <div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                    <li>标识“<em>*</em>”的选项为必填项，其余为选填项。</li>
                    <li>商店相关信息设置，请谨慎填写信息。</li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="mian-info">
                    <form enctype="multipart/form-data" name="theForm" action="?act=post" method="post">
                        <?php $_from = $this->_var['group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'group');$this->_foreach['body_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['body_group']['total'] > 0):
    foreach ($_from AS $this->_var['group']):
        $this->_foreach['body_group']['iteration']++;
?>
                        <div class="switch_info shopConfig_switch" <?php if ($this->_foreach['body_group']['iteration'] != 1): ?>style="display:none"<?php endif; ?>>
                            <?php $_from = $this->_var['group']['vars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'var');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['var']):
?>
                                <?php echo $this->fetch('library/shop_config_form.lbi'); ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <div class="item">
                                <div class="label">&nbsp;</div>
                                <div class="label_value info_btn">
                                    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" >	
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'jquery.purebox.js')); ?>
    <script type="text/javascript" src="js/jquery.picTip.js"></script>
    <script type="text/javascript" src="js/region.js"></script>
    <script type="text/javascript">
	$(function(){
		//地区三级联动调用
		$.levelLink();
		
		$('.nyroModal').nyroModal();
		
		/*控制短信接口显示*/
		var id = $(".sms_type").data('val');
		$("form[name='theForm'] :input[name='value[" + id + "]']").each(function(index, element) {
			if($(element).is(':checked')){
				if($(element).val() == 0){
					$(".ali_appkey").hide();
					$(".ali_secretkey").hide();
				}else if($(element).val() == 1){
					$(".sms_ecmoban_password").hide();
					$(".sms_ecmoban_user").hide();
				}
			}
		});

		$(".evnet_sms_type").change(function(){
			var T = $(this);
			
			if(T.val() > 0){
				$(".sms_ecmoban_password").hide();
				$(".sms_ecmoban_user").hide();
				$(".ali_appkey").show();
				$(".ali_secretkey").show();
			}else{
				$(".sms_ecmoban_password").show();
				$(".sms_ecmoban_user").show();
				$(".ali_appkey").hide();
				$(".ali_secretkey").hide();
			}
		});
	});


	/*url重写验证*/
	var ReWriteSelected = null;
	var ReWriteRadiobox = document.getElementsByName("value[209]");
	
	for (var i=0; i<ReWriteRadiobox.length; i++)
	{
	  if (ReWriteRadiobox[i].checked)
	  {
		ReWriteSelected = ReWriteRadiobox[i];
	  }
	}
	
	function ReWriterConfirm(sender)
	{
	  if (sender == ReWriteSelected) return true;
	  var res = true;
	  if (sender != ReWriteRadiobox[0]) {
		var res = confirm('<?php echo $this->_var['rewrite_confirm']; ?>');
	  }
	
	  if (res==false)
	  {
		  ReWriteSelected.checked = true;
	  }
	  else
	  {
		ReWriteSelected = sender;
	  }
	  return res;
	}
	
	function addCon_amount(obj)
	{  
	  var obj = $(obj);
	  var tbl = obj.parents('#consumtable');
	  var fald = true;
	  var error = "";
	  var volumeNum = obj.siblings("input");
	  volumeNum.each(function(index,element){
		var val = $(this).val(); 
		if(val == ""){
			$(this).addClass("error");
			fald = false;
			error = "类型和税率不能为空";
		}else if(!(/^[0-9]+.?[0-9]*$/.test(val)) && index == 1){
			$(this).addClass("error");
			fald = false;
			error = "税率必须为数字";
		}else{
			$(this).removeClass("error");
			fald = true;
		}
	  });
	  if(fald == true){
		  var input = tbl.find('p:first').clone();
		  input.addClass("mt10");
		  input.find("input[type='button']").remove();
		  input.find(".form_prompt").remove();
		  input.append("<a href='javascript:;' class='removeV' onclick='removeCon_amount(this)'><img src='images/no.gif' title='删除'></a>")
		  tbl.append(input);
		  volumeNum.val("");
	  }else{
		obj.next(".form_prompt").find(".error").remove();
		obj.next(".form_prompt").append("<label class='error'><i class='icon icon-exclamation-sign'></i>"+error+"</label>"); 
	  }
	}

	function removeCon_amount(obj)
	{
		var obj = $(obj);
		obj.parent('p').remove();
	}
    </script>
</body>
</html>
