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
<link href="<?php echo $this->_var['ecs_css_suggest']; ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/purebox.css">

<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js,jquery.divbox.js,common.js,shopping_flow.js,region.js,utils.js')); ?>
<script type="text/javascript" src="js/calendar.php?lang=<?php echo $this->_var['cfg_lang']; ?>"></script>
<link href="js/calendar/calendar.min.css" rel="stylesheet" type="text/css" />
<link href="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/merchants.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/sc_common.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/rotate3di.js"></script>
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/scroll_city.js"></script>

<script type="text/javascript">
	function openDiv() {
		if(document.getElementById('shop_categoryMain_id')){
			var shop_categoryMain = document.getElementById('shop_categoryMain_id');//  主营类目
			var addCategoryMain_Id = document.getElementById('addCategoryMain_Id');//  一级类目
			addCategoryMain_Id.value = shop_categoryMain.value;
			selectChildCate(shop_categoryMain.value);
		}
		
		$("#divSCA").OpenDiv();
	}

	function closeDiv() {
		$("#divSCA").CloseDiv();	
	}
</script>
</head>
<body>
<?php echo $this->fetch('library/page_header_merchants_flow.lbi'); ?>

<div class="merSteps">
	<div class="w1200">
	<?php if ($this->_var['sid'] == 0): ?>
	<div class="panel">
		<div class="panel-nav">
			
			<div class="progress-item ongoing">
				<div class="progress-desc">入驻选择</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
			</div>
		</div>
		
		<div class="panel-content">
			<div class="bg-top"></div>
			<div class="bg-warp">
				<a href="merchants_steps.php?deg=guide&sid=1"><?php echo $this->_var['lang']['I_guide']; ?></a>
				<a href="merchants_steps.php?deg=supplierz&sid=1"><?php echo $this->_var['lang']['I_supplier']; ?></a>
			</div>
		</div>
	</div>
	<?php elseif ($this->_var['sid'] == 1): ?>
    <form id="stepForm" action="merchants_steps_action.php" method="post" name="stepForm">
    <div class="panel">
        <div class="panel-nav">
        	
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>ongoing<?php elseif ($this->_var['sid'] == 2): ?>passed<?php elseif ($this->_var['sid'] == 3): ?>passed<?php else: ?>passed<?php endif; ?>">
                <div class="number">1</div>
                <div class="progress-desc">入驻须知</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>ongoing<?php elseif ($this->_var['sid'] == 3): ?>passed<?php else: ?>passed<?php endif; ?>">
                <div class="number">2</div>
                <div class="progress-desc">公司信息认证</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>tobe<?php elseif ($this->_var['sid'] == 3): ?>ongoing<?php else: ?>passed<?php endif; ?>">
                <div class="number">3</div>
                <div class="progress-desc">店铺信息认证</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>tobe<?php elseif ($this->_var['sid'] == 3): ?>tobe<?php else: ?>ongoing<?php endif; ?>">
                <div class="number">4</div>
                <div class="progress-desc">等待审核</div>
            </div>
        </div>
        <div class="panel-content">
        	<div class="bg-top"></div>
            <div class="bg-warp">
                <div class="title">协议确定</div>
                <div class="textareay">
                    <div class="agreement">
                        <?php echo $this->_var['steps']['article_centent']; ?>
                    </div>
                </div>
                <div class="btn-group">
                    <input name="agreement" type="hidden" value="1" />
                    <input name="nextStepBtn" class="btn" type="submit" value="同意以上协议，下一步" />
                </div>
            </div>
            <div class="bg-bottom"></div>
        </div>
    </div>    
    </form>
	<?php else: ?>
	<div class="panel">
    	<div class="panel-nav">
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>ongoing<?php elseif ($this->_var['sid'] == 2): ?>passed<?php elseif ($this->_var['sid'] == 3): ?>passed<?php else: ?>passed<?php endif; ?>">
                <div class="number">1</div>
                <div class="progress-desc">入驻须知</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>ongoing<?php elseif ($this->_var['sid'] == 3): ?>passed<?php else: ?>passed<?php endif; ?>">
                <div class="number">2</div>
                <div class="progress-desc">公司信息认证</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>tobe<?php elseif ($this->_var['sid'] == 3): ?>ongoing<?php else: ?>passed<?php endif; ?>">
                <div class="number">3</div>
                <div class="progress-desc">店铺信息认证</div>
                <div class="arrow-background"></div>
                <div class="arrow-foreground"></div>
            </div>
            <div class="progress-item <?php if ($this->_var['sid'] == 1): ?>tobe<?php elseif ($this->_var['sid'] == 2): ?>tobe<?php elseif ($this->_var['sid'] == 3): ?>tobe<?php else: ?>ongoing<?php endif; ?>">
                <div class="number">4</div>
                <div class="progress-desc">等待审核</div>
            </div>
        </div>
        <?php if ($this->_var['step'] != 'stepSubmit'): ?>
        <form enctype="multipart/form-data" id="stepForm" method="post" action="merchants_steps_action.php" name="stepForm" onsubmit="return validate();">
        	<div class="panel-content">
                <div class="bg-top"></div>
                <div class="bg-warp">
                	<div class="title">
                        <span><?php echo $this->_var['process']['process_title']; ?></span>
                    </div>
                    <?php $_from = $this->_var['steps_title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'title');if (count($_from)):
    foreach ($_from AS $this->_var['title']):
?>
                        <?php if ($this->_var['title']['special_type'] == 1 && $this->_var['title']['fields_special'] != ''): ?>
                        <div class="btn-group mt0">
                        <?php echo $this->_var['title']['fields_special']; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($this->_var['title']['steps_style'] == 0): ?>
                            <?php echo $this->fetch('library/basic_type.lbi'); ?>
                        <?php elseif ($this->_var['title']['steps_style'] == 1): ?>
                            <?php echo $this->fetch('library/shop_type.lbi'); ?>
                        <?php elseif ($this->_var['title']['steps_style'] == 2): ?>
                            <?php echo $this->fetch('library/cate_type.lbi'); ?>
                        <?php elseif ($this->_var['title']['steps_style'] == 3): ?>
                            <?php echo $this->fetch('library/brank_type.lbi'); ?> 
                        <?php elseif ($this->_var['title']['steps_style'] == 4): ?>
                            <?php echo $this->fetch('library/shop_info.lbi'); ?>
                        <?php endif; ?>
                        <?php if ($this->_var['title']['special_type'] == 2 && $this->_var['title']['fields_special'] != ''): ?>
                        <div class="btn-group">
                            <?php if ($this->_var['brandView'] != 'addBrand'): ?>
                                <?php echo $this->_var['title']['fields_special']; ?>
                            <?php endif; ?>    
                        </div>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['brandView'] != 'addBrand'): ?>
                        <div class="btn-group mt0" style="text-align:center;">
                            <input name="numAdd" value="1" id="numAdd" type="hidden" />
                            <input name="pid_key" type="hidden" value="<?php echo $this->_var['pid_key']; ?>" />
                            <input name="sid" type="hidden" value="<?php echo $this->_var['sid']; ?>" />
                            <input name="step" type="hidden" value="<?php echo $this->_var['step']; ?>" />
                            <?php if ($this->_var['brandView'] == 'brandView'): ?>
                            <input name="brandView" type="hidden" value="<?php echo $this->_var['brandView']; ?>" />
                            <input id="nextStepBtn" class="btnOrg" type="submit" value="保存">
                            <input class="btnOrgBor" type="button" onclick="location.href='merchants_steps.php?step=stepThree&pid_key=2'" value="返回">
                            <?php elseif ($this->_var['brandView'] == 'add_brand'): ?>
                            <input name="brandView" type="hidden" value="<?php echo $this->_var['brandView']; ?>" />
                            <?php else: ?>
                                <?php if ($this->_var['process']['fields_next']): ?>
                                <input id="nextStepBtn" class="btn" type="submit" value="<?php echo $this->_var['process']['fields_next']; ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->_var['pid_key'] > 1 || $this->_var['sid'] >= 3): ?>
                            <input class="btn btn-w" id="js-pre-step" type="button" value="上一步" />
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="bg-bottom"></div>
            </div>
        </form>
        <?php else: ?>
			<div class="panel-content">
            	<div class="bg-top"></div>
                <div class="bg-warp pannel_end">
                	<div class="settled-state">
                        <?php if ($this->_var['shop_info']['merchants_audit'] == 0): ?>
                        <span>正在审核中...</span>
                        <?php elseif ($this->_var['shop_info']['merchants_audit'] == 1): ?>
                        <span>审核已通过...</span>
                        <?php elseif ($this->_var['shop_info']['merchants_audit'] == 2): ?>
                        <span>未审核通过...</span>
                            <?php if ($this->_var['shop_info']['merchants_message']): ?>
                            <span><?php echo $this->_var['shop_info']['merchants_message']; ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                	</div>
                	<h3 class="ordertitle">感谢您在本店申请商家入驻！</h3>
                    <?php if ($this->_var['shop_info']['merchants_audit'] == 1): ?>
                    <div class="item">
                        <div class="label">您的商家入驻管理中心登录账号：</div>
                        <strong class="orange2"><?php echo $this->_var['shop_info']['hopeLoginName']; ?></strong>
                    </div>
                    <div class="item" style="display:none">
                    	<div class="label">密码：</div>
                    	<strong class="orange2">已发送手机短信，请您查收</strong>
                    </div>
                    <?php endif; ?>
                    
                    <div class="item">
                    	<div class="label">期望店铺名称：</div>
                        <strong class="orange2"><?php echo $this->_var['shop_info']['shop_name']; ?></strong>
                    </div>
                    
                    <div class="item"><div class="label">店铺描述：</div><strong class="orange2"><?php echo $this->_var['shop_info']['shop_class_keyWords']; ?></strong></div>
                    
                    <div class="setted-footer"><a href="index.php">返回首页</a><a href="user.php">用户中心</a><a href="user.php?act=merchants_upgrade">商家等级</a></div>
                </div>
                <div class="bg-bottom"></div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    </div>
</div>

<?php echo $this->fetch('library/page_footer.lbi'); ?> 
<script type="text/javascript" src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/js/jquery.purebox.js"></script>
</body>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
var compare_no_goods = "<?php echo $this->_var['lang']['compare_no_goods']; ?>";
var btn_buy = "<?php echo $this->_var['lang']['btn_buy']; ?>";
var is_cancel = "<?php echo $this->_var['lang']['is_cancel']; ?>";
var select_spe = "<?php echo $this->_var['lang']['select_spe']; ?>";

$(function(){
	$("#js-pre-step").click(function(){
		var pid_key=<?php echo $this->_var['pid_key']; ?>-2;
		var step='<?php echo $this->_var['step']; ?>';
		if(step>-1)
		{
			location.href="merchants_steps.php?step="+step+"&pid_key="+pid_key;	
		}
		else
		{
			history.go(-1);
		}
	})
});

function validate(){
	
	var cate_result = get_element('shoprz_type', 'shoprz_Html', 0, '请选择期望店铺类型');
	
	if(cate_result == false){
		return false;
	}
	
	var title_brand_list = document.getElementById('title_brand_list');
	
	if(title_brand_list && title_brand_list.value == 0){
		var title_brand_listHTML =  document.getElementById('title_brand_listHTML');
		title_brand_listHTML.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp请添加品牌';
		return false;
	}
	
	if(document.getElementById('subShoprz_type')){
		var subShoprz = document.getElementById('subShoprz_type');
		if(subShoprz.style.display == 'block' || subShoprz.style.display == ''){
			if(subShoprz.value == 0){
				if(document.getElementById('subShoprz_Html')){
					var subShoprz_Html = document.getElementById('subShoprz_Html');
					subShoprz_Html.innerHTML = '请选择旗舰店类型';
					
					return false;
				}
			}else{
				if(document.getElementById('subShoprz_Html')){
					var subShoprz_Html = document.getElementById('subShoprz_Html');
					subShoprz_Html.innerHTML = '';
				}
			}
		}
	}
	
	var cate_result = get_element('shop_categoryMain_id', 'cate_Html', 0, '请选择主营类目');
	
	if(cate_result == false){
		return false;
	}else{
		
		if(document.getElementById('shop_categoryMain_id')){
			
			var seller_category = $(".seller_category").size();
			
			if(seller_category == 0){
				$("#categorySpan").removeClass("hide");
				return false;
			}else{
				$("#categorySpan").addClass("hide");
			}
		}
	}
	
	var brandName_result = get_element('brandName', 'brandNameHTML', '', '请填写品牌名');
	
	if(brandName_result == false){
		return false;
	}
	
	var brandFirst_result = get_element('brandFirstChar', 'brandFirstCharHTML', '', '请填写品牌首字母');
	if(brandFirst_result == false){
		return false;
	}

	var text_brandView_brandLogo = document.getElementById('textBrandLogo');
	if(text_brandView_brandLogo && text_brandView_brandLogo.value == ''){
		var brandLogo_result = get_element('brandLogo', 'textBrandLogoHTML', '', '请上传品牌LOGO');
		
		if(brandLogo_result == false){
			var textBrandLogo_result = get_element('textBrandLogo', '', '', '');
			if(textBrandLogo_result == false){
				return false;
			}
		}
	}

	var brandType_result = get_element('brandType', 'brandTypeHTML', 0, '请选择品牌类型');
	
	if(brandType_result == false){
		return false;
	}
	
	var operateType_result = get_element('brand_operateType', 'operateTypeHTML', 0, '请选择经营类型');
	
	if(operateType_result == false){
		return false;
	}
	
	var brandEndTime_result = get_element('ec_brandEndTime', 'brandEndTimeHTML', '', '请填写品牌使用期限');
	
	if(brandEndTime_result == false){
		var textBrandLogo_result = get_element('brandEndTime_permanent', '', false, '', 'checkbox');

		if(textBrandLogo_result == false){
			return false;
		}
	}
	
	var rz_shopName_result = get_element('rz_shopName', 'rz_shopNameHTML', '', '请填写期望店铺名称');
	
	if(rz_shopName_result == false){
		return false;
	}
	
	var hopeLoginName_result = get_element('hopeLoginName', 'hopeLoginNameHTML', '', '请填写期望店铺登录用户名');
	
	if(hopeLoginName_result == false){
		return false;
	}
	
	<?php if ($this->_var['choose_process'] == 1): ?>
	<?php $_from = $this->_var['steps_title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'title');if (count($_from)):
    foreach ($_from AS $this->_var['title']):
?>
		<?php $_from = $this->_var['title']['cententFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fields');if (count($_from)):
    foreach ($_from AS $this->_var['fields']):
?>
			<?php if ($this->_var['fields']['chooseForm'] == 'input' || $this->_var['fields']['chooseForm'] == 'textarea'): ?> //纯文本
				var input_<?php echo $this->_var['fields']['textFields']; ?> = document.forms['stepForm'].elements['<?php echo $this->_var['fields']['textFields']; ?>'].value;
				<?php if ($this->_var['fields']['will_choose'] == 1): ?>
					if(input_<?php echo $this->_var['fields']['textFields']; ?> == ''){
						
						document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请填写<?php echo $this->_var['fields']['fieldsFormName']; ?>';
						document.forms['stepForm'].elements['<?php echo $this->_var['fields']['textFields']; ?>'].focus();
						
						return false;
					}else{
						document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
					}
				<?php endif; ?>
			<?php elseif ($this->_var['fields']['chooseForm'] == 'other'): ?> //地区、上传文件、日期
				<?php if ($this->_var['fields']['otherForm'] == 'textArea'): ?>
					//地区
					var selCountries_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('selCountries_<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['sn']; ?>').value;
					var selProvinces_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('selProvinces_<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['sn']; ?>').value;
					var selCities_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('selCities_<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['sn']; ?>').value;
					var selDistricts_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('selDistricts_<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['sn']; ?>').value;
					
					var selD_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('selDistricts_<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['sn']; ?>').style.display;
					
					<?php if ($this->_var['fields']['will_choose'] == 1): ?>
						if(selCountries_<?php echo $this->_var['fields']['textFields']; ?> == 0){
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请选择<?php echo $this->_var['fields']['fieldsFormName']; ?>';
							return false;	
						}else{
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
						}
						
						if(selProvinces_<?php echo $this->_var['fields']['textFields']; ?> == 0){
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请选择<?php echo $this->_var['fields']['fieldsFormName']; ?>';
							return false;
						}else{
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
						}
						
						if(selCities_<?php echo $this->_var['fields']['textFields']; ?> == 0){
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请选择<?php echo $this->_var['fields']['fieldsFormName']; ?>';
							return false;
						}else{
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
						}
						
						if(selD_<?php echo $this->_var['fields']['textFields']; ?> != 'none'){	
							if(selDistricts_<?php echo $this->_var['fields']['textFields']; ?> == 0){
								document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请选择<?php echo $this->_var['fields']['fieldsFormName']; ?>';
								return false;	
							}else{
								document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
							}
						}
					<?php endif; ?>
				<?php elseif ($this->_var['fields']['otherForm'] == 'dateFile'): ?> 	
					//上传文件
					var input_<?php echo $this->_var['fields']['textFields']; ?> = document.forms['stepForm'].elements['<?php echo $this->_var['fields']['textFields']; ?>'].value;
					var text_<?php echo $this->_var['fields']['textFields']; ?> = document.forms['stepForm'].elements['text_<?php echo $this->_var['fields']['textFields']; ?>'].value;
					
					<?php if ($this->_var['fields']['will_choose'] == 1): ?>
						if(input_<?php echo $this->_var['fields']['textFields']; ?> == '' && text_<?php echo $this->_var['fields']['textFields']; ?> == ''){
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请上传<?php echo $this->_var['fields']['fieldsFormName']; ?>照片';
							return false;	
						}else{
							document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
						}
					<?php endif; ?>
				<?php elseif ($this->_var['fields']['otherForm'] == 'dateTime'): ?> 
					//日期
					<?php $_from = $this->_var['fields']['dateTimeForm']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('dk', 'date');if (count($_from)):
    foreach ($_from AS $this->_var['dk'] => $this->_var['date']):
?>
						var dateTime_<?php echo $this->_var['fields']['textFields']; ?> = document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>_<?php echo $this->_var['dk']; ?>').value;
						<?php if ($this->_var['fields']['will_choose'] == 1): ?>
							if(dateTime_<?php echo $this->_var['fields']['textFields']; ?> == ''){
								document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请填写<?php echo $this->_var['fields']['fieldsFormName']; ?>';
								return false;
							}else{
								document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
							}
						<?php endif; ?>	
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<?php endif; ?>
			<?php elseif ($this->_var['fields']['chooseForm'] == 'select'): ?> 
			 	var select_<?php echo $this->_var['fields']['textFields']; ?> = document.forms['stepForm'].elements['<?php echo $this->_var['fields']['textFields']; ?>'].value;
				<?php if ($this->_var['fields']['will_choose'] == 1): ?>
					if(select_<?php echo $this->_var['fields']['textFields']; ?> == 0){
						document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '请选择<?php echo $this->_var['fields']['fieldsFormName']; ?>';
						document.forms['stepForm'].elements['<?php echo $this->_var['fields']['textFields']; ?>'].focus();
						
						return false;
					}else{
						document.getElementById('<?php echo $this->_var['fields']['textFields']; ?>').innerHTML = '';
					}	
				<?php endif; ?>	
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endif; ?>
}

function $id(element) {
  return document.getElementById(element);
}

function get_element(element, elementHTML, where, textCen, type){
	if($id(element)){
		
		var where_type;
		
		if(type != 'checkbox'){
			var element = $id(element).value;
			where_type = (element == where);
		}else{
			where_type = ($id(element).checked == false);
		}
		
		if(where_type){
			if($id(elementHTML)){
				if($id(elementHTML) && elementHTML != ''){
					$id(elementHTML).innerHTML = textCen;
				}
			}
			
			return false;
		}else{
			if($id(elementHTML) && elementHTML != ''){
				$id(elementHTML).innerHTML = '';
			}
			
			return true;
		}
	}
}

function addBrandTable(obj)
{  
	var add_num = 1000;
	var num = document.getElementById('numAdd').value;
	if(num < add_num){
		var src  = obj.parentNode.parentNode;
		var idx  = rowindex(src);
		var tbl  = document.getElementById('brand-table');
		var row  = tbl.insertRow(idx + 1);
		//var cell = row.insertCell(-1);
		row.innerHTML = src.innerHTML.replace(/(.*)(addBrandTable)(.*)(\[)(\+)/i, "$1removeBrandTable$3$4-").replace('"expiredDate_permanent"','\"expiredDate_permanent'+num+'\"');
		row.innerHTML = row.innerHTML.replace('"expiredDate_permanent"','\"expiredDate_permanent'+num+'\"');
	  	
		num++;
		document.getElementById('numAdd').value = num;
	}else{
		alert('最多添加' + add_num +'次');
	}
	
	for(i=0;i<num;i++){
		var expiredDate = document.getElementsByName("ec_expiredDateInput[]");
		expiredDate[i].id = 'expiredDateInput_' + i;
	}
}

function removeBrandTable(obj,b_fid)
{
	if(b_fid > 0){
		if (confirm('确定删除这条品牌资质信息吗')){
		   location.href = 'merchants_steps.php?step=<?php echo $this->_var['step']; ?>&pid_key=<?php echo $this->_var['b_pidKey']; ?>&ec_shop_bid=<?php echo $this->_var['ec_shop_bid']; ?>&del_bFid=' + b_fid + '&brandView=brandView';
	   }
	}else{
		var row = rowindex(obj.parentNode.parentNode);
		var tbl = document.getElementById('brand-table');
		
		tbl.deleteRow(row);
		
		var num = document.getElementById('numAdd').value;
		num--;
		document.getElementById('numAdd').value = num;
		
		for(i=0;i<num;i++){
			var radioCheckbox_val = document.getElementsByName("radioCheckbox_val[]");
			radioCheckbox_val[i].value = i;
		}
	}
}  

//永久 清空开始时间和结束时间
function get_authorizeCheckBox(f,id_start,id_end, start_date, end_date){
	
	if(id_start != '' || id_end != ''){
		
		if(id_start == ''){
			id_start = 'n_s';
		}
		
		if(id_end == ''){
			id_end = 'n_e';
		}
		
		var start_time = document.getElementById(id_start);
		var end_time = document.getElementById(id_end);
		
		if(f.checked){
			start_time.value = '';
			end_time.value = '';
		}else{
			if(start_date != '' || end_date != ''){
				start_time.value = start_date;
				end_time.value = end_date;
			}
		}
	}
}

function get_categoryId_permanent(f, permanent_date, dt_id){
	var categoryId = document.getElementById('categoryId_date_' + dt_id);
	if(f.checked){
		categoryId.value = '';
	}else{
		if(permanent_date != ''){
			categoryId.value = permanent_date;
		}
	}
}

function get_expiredDate_permanent(f, expiredDateInput, b_fid){
	if(b_fid > 0){
		var expiredDate = document.getElementById('expiredDateInput_' + b_fid);
		if(f.checked){
			$(f).parent().addClass("cart-checkbox-checked");
			expiredDate.value = '';
		}else{
			$(f).parent().removeClass("cart-checkbox-checked");
			if(expiredDateInput != ''){
				expiredDate.value = expiredDateInput;
			}
		}
	}else{
		
		var DateInput = document.getElementsByName("ec_expiredDateInput[]");
		var permanent = document.getElementsByName("ec_expiredDate_permanent[]");
		for(i=0; i<permanent.length; i++){
			if(permanent[i].checked){
				if(DateInput[i].value != ''){
					DateInput[i].value = '';
				}
			}
		}
	}
}

function get_brandEndTime_permanent(f, brandEndTime){
	var ec_brandEndTime = document.getElementById('ec_brandEndTime');
	if(f.checked){
		$(f).parent().addClass("cart-checkbox-checked");
		ec_brandEndTime.value = '';
	}else{
		$(f).parent().removeClass("cart-checkbox-checked");
		if(brandEndTime != ''){
			ec_brandEndTime.value = brandEndTime;
		}
	}
}

function get_deleteBrand(bid){
	if (confirm('确定删除这条品牌资质信息吗')){
		location.href = 'merchants_steps.php?step=<?php echo $this->_var['step']; ?>&pid_key=<?php echo $this->_var['b_pidKey']; ?>&ec_shop_bid=' + bid + '&del=deleteBrand';
	}
}
</script>
</html>
