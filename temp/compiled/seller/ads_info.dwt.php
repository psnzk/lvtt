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
                <!--start-->
                <div class="info_warp">
                <form action="ads.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
                  <table width="100%" id="general-table" class="table_item" cellpadding="0" cellspacing="0">
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['ad_type']; ?>：</td>
                      <td>
                         <div class="checkbox_items">
                             <label><input name="ad_type" type="radio" class="checkbox" value="0" <?php if ($this->_var['ads']['ad_type'] == 0): ?>checked="checked"<?php endif; ?> onclick="get_ad_type(this.value);" /><?php echo $this->_var['lang']['ad_type1']; ?></label>
                             <label><input name="ad_type" type="radio" class="checkbox" value="1" <?php if ($this->_var['ads']['ad_type'] == 1): ?>checked="checked"<?php endif; ?> onclick="get_ad_type(this.value);" /><?php echo $this->_var['lang']['ad_type2']; ?></label>
                         </div>
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['ad_name']; ?>：</td>
                      <td>
                        <input type="text" name="ad_name" value="<?php echo $this->_var['ads']['ad_name']; ?>" size="35"  class="text" />
                        <label class="blue_label"><?php echo $this->_var['lang']['ad_name_notic']; ?></label>
                        <!--广告位 by wu-->
                        <div class="ad-set">
                            <div class="num-set">
                                <strong>广告名称序号：</strong>
                                <input type="button" value="-" class="num_add">
                                <input type='text' value="1" class="num_id" name="num_id">
                                <input type="button" value="+" class="num_reduce">
                            </div>
                            <div class="cat-set">
                                <strong>选择广告分类ID：</strong>
                                <input type='text' value="" class="cat_id" placeholder="请输入分类ID" name="cat_id">
                                <select name="catFirst" onchange="selectCat(this)" rank="1">
                                    <option value="-1">请选择分类</option>
                                    <?php $_from = $this->_var['catFirst']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                                    <option value="<?php echo $this->_var['cat']['cat_id']; ?>">[<?php echo $this->_var['cat']['cat_id']; ?>]<?php echo $this->_var['cat']['cat_name']; ?></option>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                <select>
                            </div>
                        </div>
                        <!--广告位 by wu-->
                      </td>
                    </tr>
                    
                    <tr id="ad_type_2" style="display:<?php if ($this->_var['ads']['ad_type'] == 0): ?>none<?php else: ?><?php endif; ?>">
                      <td  class="label"><?php echo $this->_var['lang']['goods_name']; ?>：</td>
                      <td>
                        <input type="text" name="goods_name" value="<?php echo $this->_var['ads']['goods_name']; ?>" size="55" class="text"/>
                        <label class="blue_label"><?php echo $this->_var['lang']['ad_name_notic2']; ?></label>
                      </td>
                    </tr>
                    <?php if ($this->_var['action'] == "add"): ?>
                      <tr>
                        <td class="label"><?php echo $this->_var['lang']['media_type']; ?>：</td>
                        <td>
                         <select name="media_type" onchange="showMedia(this.value)" class="select">
                         <option value='0'><?php echo $this->_var['lang']['ad_img']; ?></option>
                         <option value='1'><?php echo $this->_var['lang']['ad_flash']; ?></option>
                         <option value='2'><?php echo $this->_var['lang']['ad_html']; ?></option>
                         <option value='3'><?php echo $this->_var['lang']['ad_text']; ?></option>
                         </select>
                        </td>
                      </tr>
                    <?php else: ?>
                        <input type="hidden" name="media_type" value="<?php echo $this->_var['ads']['media_type']; ?>" />
                    <?php endif; ?>
                    <tr>
                      <td class="label"><?php echo $this->_var['lang']['position_id']; ?>：</td>
                      <td>
                        <select name="position_id" class="select">
                        <option value='0'><?php echo $this->_var['lang']['outside_posit']; ?></option>
                        <?php echo $this->html_options(array('options'=>$this->_var['position_list'],'selected'=>$this->_var['ads']['position_id'])); ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['start_end_date']; ?>：</td>
                      <td>
                        <div class="text_time" id="text_time1">
                            <input name="start_time" type="text" id="start_time" size="22" value='<?php echo $this->_var['ads']['start_time']; ?>' readonly="readonly" />
                            <input name="selbtn1" type="button" id="selbtn1"/>
                        </div>
                        <span class="bolang">&nbsp;&nbsp;~&nbsp;&nbsp;</span>
                        <div class="text_time" id="text_time2">
                            <input name="end_time" type="text" id="end_time" size="22" value='<?php echo $this->_var['ads']['end_time']; ?>' readonly="readonly" />
                            <input name="selbtn2" type="button" id="selbtn2"/>
                        </div>
                      </td>
                    </tr>
                  <?php if ($this->_var['ads']['media_type'] == 0 || $this->_var['action'] == "add"): ?>
                    <tbody id="0">
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['ad_link']; ?>：</td>
                      <td>
                        <input type="text" name="ad_link" value="<?php echo $this->_var['ads']['ad_link']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['upfile_img']; ?>：</td>
                      <td>
                        <input type='file' name='ad_img' size='35' class="file" />
                        <label class="blue_label" id="AdCodeImg"><?php echo $this->_var['lang']['ad_code_img']; ?></label>
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['img_url']; ?>：</td>
                      <td><input type="text" name="img_url" value="<?php echo $this->_var['url_src']; ?>" size="35" class="text" /></td>
                    </tr>
                    </tbody>
                  <?php endif; ?>
                  <?php if ($this->_var['ads']['media_type'] == 1 || $this->_var['action'] == "add"): ?>
                    <tbody id="1" style="<?php if ($this->_var['ads']['media_type'] != 1 || $this->_var['action'] == 'add'): ?>display:none<?php endif; ?>">
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['upfile_flash']; ?>：</td>
                      <td>
                        <input type='file' name='upfile_flash' size='35' class="file" />
                        <label class="blue_label" id="AdCodeFlash"><?php echo $this->_var['lang']['ad_code_flash']; ?></label>
                      </td>
                    </tr>
                    <tr>
                      <td class="label"><?php echo $this->_var['lang']['flash_url']; ?>：</td>
                      <td>
                        <input type="text" name="flash_url" value="<?php echo $this->_var['flash_url']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    </tbody>
                  <?php endif; ?>

                  <?php if ($this->_var['ads']['media_type'] == 2 || $this->_var['action'] == "add"): ?>
                    <tbody id="2" style="<?php if ($this->_var['ads']['media_type'] != 2 || $this->_var['action'] == 'add'): ?>display:none<?php endif; ?>">
                      <tr>
                        <td  class="label"><?php echo $this->_var['lang']['enter_code']; ?>：</td>
                        <td><textarea name="ad_code" cols="50" rows="7" class="textarea"><?php echo $this->_var['ads']['ad_code']; ?></textarea></td>
                      </tr>
                    </tbody>
                  <?php endif; ?>

                  <?php if ($this->_var['ads']['media_type'] == 3 || $this->_var['action'] == "add"): ?>
                    <tbody id="3" style="<?php if ($this->_var['ads']['media_type'] != 3 || $this->_var['action'] == 'add'): ?>display:none<?php endif; ?>">
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['ad_link']; ?>：</td>
                      <td>
                        <input type="text" name="ad_link2" value="<?php echo $this->_var['ads']['ad_link']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['ad_code']; ?>：</td>
                      <td><textarea name="ad_text" cols="40" rows="3" class="textarea"><?php echo $this->_var['ads']['ad_code']; ?></textarea></td>
                    </tr>
                    </tbody>
                 <?php endif; ?>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['background_color']; ?>：</td>
                      <td>
                        <input type="text" name="link_color" value="<?php echo $this->_var['ads']['link_color']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['enabled']; ?>：</td>
                      <td>
                        <div class="checkbox_items">
                            <label><input type="radio" class="checkbox" name="enabled" value="1" <?php if ($this->_var['ads']['enabled'] == 1): ?> checked="true" <?php endif; ?> /><?php echo $this->_var['lang']['is_enabled']; ?></label>
                            <label><input type="radio" class="checkbox" name="enabled" value="0" <?php if ($this->_var['ads']['enabled'] == 0): ?> checked="true" <?php endif; ?> /><?php echo $this->_var['lang']['no_enabled']; ?></label>
                        </div>
                      </td>
                    </tr>
                    <tr style="display:none">
                      <td  class="label"><?php echo $this->_var['lang']['home_rec_mer_pro']; ?>：</td>
                      <td>
                        <div class="checkbox_items">
                        <label><input type="checkbox" class="checkbox" name="is_new" value="1" <?php if ($this->_var['ads']['is_new'] == 1): ?> checked="true" <?php endif; ?> /><?php echo $this->_var['lang']['new_adv']; ?></label>
                        <label><input type="checkbox" class="checkbox" name="is_hot" value="1" <?php if ($this->_var['ads']['is_hot'] == 1): ?> checked="true" <?php endif; ?> /><?php echo $this->_var['lang']['hot_adv']; ?></label>
                        <label><input type="checkbox" class="checkbox" name="is_best" value="1" <?php if ($this->_var['ads']['is_best'] == 1): ?> checked="true" <?php endif; ?> /><?php echo $this->_var['lang']['best_adv']; ?></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['link_man']; ?>：</td>
                      <td>
                        <input type="text" name="link_man" value="<?php echo $this->_var['ads']['link_man']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['link_email']; ?>：</td>
                      <td>
                        <input type="text" name="link_email" value="<?php echo $this->_var['ads']['link_email']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr>
                      <td  class="label"><?php echo $this->_var['lang']['link_phone']; ?>：</td>
                      <td>
                        <input type="text" name="link_phone" value="<?php echo $this->_var['ads']['link_phone']; ?>" size="35" class="text" />
                      </td>
                    </tr>
                    <tr class="no-line">
                       <td class="label">&nbsp;</td>
                       <td class="pt20 pb20">
                        <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button fl" />
                        <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button button_reset fl" />
                        <input type="hidden" name="act" value="<?php echo $this->_var['form_act']; ?>" />
                        <input type="hidden" name="id" value="<?php echo $this->_var['ads']['ad_id']; ?>" />
                      </td>
                    </tr>
                 </table>
                </form>
                </div>					
                <!--end-->
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript">
	var ad_model=jQuery.parseJSON('<?php echo $this->_var['ad_model']; ?>');
	var action='<?php echo $this->_var['action']; ?>';

	// 存在广告模型
	if(ad_model && ad_model.ad_type>0)
	{
		var ad_name=$("input[name=ad_name]");
		var numId=$(".num_id");
		var catId=$(".cat_id");
		$(".ad-set").show();
		//$("input[name=ad_img]").css({'border':'1px solid #f00','padding':'10px'});
		if(action=='add')
		{
			changeSet();
		}
	
		// 只包含num_id
		if(ad_model.ad_type==1)
		{	
			$(".num-set").show();
		}	
		// 只包含cat_id
		if(ad_model.ad_type==2)
		{	
			$(".cat-set").show();
		}
		// 包含num_id,cat_id，分类循环广告
		if(ad_model.ad_type>2)
		{
			$(".num-set").show();
			$(".cat-set").show();
		}	
		
		/* 设置广告序号 */		
		$(".num_add").click(function(){
			if(numId.val()>1)
			{	
				numId.val(parseInt(numId.val())-1);
			}
			else
			{
				alert('广告序号最小为1');
				numId.val(1);
			}
			changeSet();
		})
	
		$(".num_reduce").click(function(){
			numId.val(parseInt(numId.val())+1);
			changeSet();
		})
		
		$(".num_id").keyup(function(){
			changeSet();
		})
		/* 设置广告序号 */
		
		/* 设置分类 ID */	
		$(".cat_id").keyup(function(){
			changeSet();
		})
		/* 设置分类 ID */
	}

	function selectCat(obj)
	{
		//分类等级，从1级开始
		var rank=obj.getAttribute('rank');
	
		if(obj.value!=0)
		{
			$.ajax({
				type:'get',
				url:'ads.php',
				data:"act=getCatList&catId=" + obj.value,
				dataType:'json',
				success:function(data)
				{
					rank=parseInt(rank)+1;
					if(data.length!=0)
					{
						
						var select="<select onchange='selectCat(this)' rank='"+rank+"'>"+"\n";
						var options="<option value='-1'>请选择分类</option>"+"\n";
						for (i = 0; i < data.length; i ++ )
						{
							options+="<option value='"+data[i].cat_id+"'>["+data[i].cat_id+"]"+data[i].cat_name+"</option>"+"\n";
						}
						select+=options+"</select>"+"\n";
						for (i = rank; i < 10; i ++ )
						{
							$(".cat-set").find("select[rank="+i+"]").remove();
						}
						$(".cat-set").append(select);
					}
					else
					{
						for (i = rank; i < 10; i ++ )
						{
							$(".cat-set").find("select[rank="+i+"]").remove();
						}
					}
				}
			})
			if(obj.value!=-1)
			{
				//将选中分类id赋给input
				$(".cat_id").val(obj.value);
				changeSet();
			}		
		}
	}

	function changeSet()
	{
		ad_name.val(ad_model.ad_model_init.replace('[num_id]',numId.val()));
		ad_name.val(ad_name.val().replace('[cat_id]',catId.val()));
		//替换merchant_id start
		var ru_id = <?php echo $this->_var['ru_id']; ?>;
		ad_name.val(ad_name.val().replace('[merchant_id]',ru_id));		
		//替换merchant_id end		
	}

	var MediaList = new Array('0', '1', '2', '3');
	
	function showMedia(AdMediaType){
		for (I = 0; I < MediaList.length; I ++){
			if (MediaList[I] == AdMediaType){
				document.getElementById(AdMediaType).style.display = "";
			}else{
				document.getElementById(MediaList[I]).style.display = "none";
			}
		}
	}
	/**
	* 检查表单输入的数据
	*/
	function validate()
	{
		validator = new Validator("theForm");
		validator.required("ad_name",     ad_name_empty);
		return validator.passed();
	}

	onload = function()
	{
		startCheckOrder();
	}
  
	function get_ad_type(val){
		var ad_type_2 = document.getElementById('ad_type_2');
		  
		if(val == 1){
			ad_type_2.style.display = '';
		}else{
			ad_type_2.style.display = 'none';
		}
	}
  
    //日期选择插件调用start sunle
	var opts1 = {
		'targetId':'start_time',//时间写入对象的id
		'triggerId':['selbtn1'],//触发事件的对象id
		'alignId':'text_time1',//日历对齐对象
		'format':'-'//时间格式 默认'YYYY-MM-DD HH:MM:SS'
	},opts2 = {
		'targetId':'end_time',
		'triggerId':['selbtn2'],
		'alignId':'text_time2',
		'format':'-'
	}

	xvDate(opts1);
	xvDate(opts2);
	//日期选择插件调用end sunle
  
</script>
</body>
</html>