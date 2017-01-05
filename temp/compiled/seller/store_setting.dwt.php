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
                    <form method="post" action="index.php?act=merchants_second" id="my_store_form" enctype="multipart/form-data">
                        <input type="hidden" name="form_submit" value="ok">
                        <dl>
                            <dt><?php if ($this->_var['priv_ru']): ?><?php echo $this->_var['lang']['steps_shop_name']; ?><?php else: ?><?php echo $this->_var['lang']['company_name']; ?><?php endif; ?>：</dt>
                            <dd><input type="text" name="shop_name" value="<?php echo $this->_var['shop_info']['shop_name']; ?>" size="40" class="text" /></dd>
                        </dl>
                        <?php if (! $this->_var['priv_ru']): ?>
                        <dl>
                            <dt><?php echo $this->_var['lang']['settled_shop_name']; ?>：</dt>
                            <dd><input type="text" name="brand_shop_name" value="<?php echo $this->_var['shop_information']['shop_name']; ?>" disabled="disabled" size="40" class="text" /></dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['expect_shop_name']; ?>：</dt>
                            <dd><input type="text" name="ec_rz_shopName" value="<?php echo $this->_var['shop_information']['rz_shopName']; ?>" disabled="disabled" size="40" class="text" /></dd>
                        </dl>
                        <dl class="setup store-logo">
                            <dt><?php echo $this->_var['lang']['display_shop_name']; ?>：</dt>
                            <dd>
                                <label class="mr10"><input type="radio" name="check_sellername" value="0" class="checkbox" <?php if ($this->_var['shop_info']['check_sellername'] == 0): ?>checked="checked"<?php endif; ?> /><?php echo $this->_var['lang']['settled_brand_shop_name']; ?></label>
                                <label class="mr10"><input type="radio" name="check_sellername" value="1" class="checkbox" <?php if ($this->_var['shop_info']['check_sellername'] == 1): ?>checked="checked"<?php endif; ?> /><?php echo $this->_var['lang']['expect_shop_name']; ?></label>
                                <label><input type="radio" name="check_sellername" value="2" class="checkbox" <?php if ($this->_var['shop_info']['check_sellername'] == 2): ?>checked="checked"<?php endif; ?> /><?php echo $this->_var['lang']['company_name']; ?></label>
                                <?php if ($this->_var['shop_info']['shopname_audit'] == 1): ?>
                                    &nbsp;&nbsp;<font class="red"><?php echo $this->_var['lang']['already_examine']; ?></font>
                                <?php else: ?>
                                    &nbsp;&nbsp;<font class="org"><?php echo $this->_var['lang']['stay_examine']; ?></font>
                                <?php endif; ?>
                            </dd>
                        </dl>
                        <?php endif; ?>
                        <dl>
                            <dt><?php echo $this->_var['lang']['02_template_select']; ?>：</dt>
                            <dd>
                                <div class="checkbox_items">
                                    <label class="mr10"><input name="templates_mode" type="radio" value="0" class="checkbox" <?php if ($this->_var['shop_info']['templates_mode'] == 0): ?>checked="checked"<?php endif; ?> />默认模板</label>
                                    <label><input name="templates_mode" type="radio" value="1" class="checkbox" <?php if ($this->_var['shop_info']['templates_mode'] == 1): ?>checked="checked"<?php endif; ?> />可视化编辑模板</label>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_title']; ?>：</dt>
                            <dd><input type="text" name="shop_title" value="<?php echo $this->_var['shop_info']['shop_title']; ?>" class="text" /></dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_keyword']; ?>：</dt>
                            <dd><input type="text" name="shop_keyword" value="<?php echo $this->_var['shop_info']['shop_keyword']; ?>" class="text" /></dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['lab_seller_site']; ?>：</dt>
                            <dd><input type="text" size="40" value="<?php echo $this->_var['shop_info']['domain_name']; ?>" name="domain_name" class="text" /></dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_country']; ?>：</dt>
                            <dd>
                                 <select name="shop_country" id="selCountries" onchange="region.changed(this, 1, 'selProvinces')" class="select">
                                 	<option value=''><?php echo $this->_var['lang']['select_please']; ?></option>
                                   	<?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
                                   		<option value="<?php echo $this->_var['region']['region_id']; ?>" <?php if ($this->_var['region']['region_id'] == $this->_var['shop_info']['country']): ?>selected<?php endif; ?>><?php echo $this->_var['region']['region_name']; ?></option>
                                   	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                 </select> 
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_province']; ?>：</dt>
                            <dd>
                                 <select name="shop_province" id="selProvinces" onchange="region.changed(this, 2, 'selCities')" class="select">
                                   <option value=''><?php echo $this->_var['lang']['select_please']; ?></option>
                                     <?php $_from = $this->_var['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
                                       <option value="<?php echo $this->_var['region']['region_id']; ?>" <?php if ($this->_var['region']['region_id'] == $this->_var['shop_info']['province']): ?>selected<?php endif; ?>> <?php echo $this->_var['region']['region_name']; ?></option>
                                     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                 </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_city']; ?>：</dt>
                            <dd>
                                <select name="shop_city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')" class="select">
                                  <option value=''><?php echo $this->_var['lang']['select_please']; ?></option>
                                    <?php $_from = $this->_var['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
                                      <option value="<?php echo $this->_var['region']['region_id']; ?>" <?php if ($this->_var['region']['region_id'] == $this->_var['shop_info']['city']): ?>selected<?php endif; ?>><?php echo $this->_var['region']['region_name']; ?></option>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['local_area']; ?>：</dt>
                            <dd>
                                <select name="shop_district" id="selDistricts" class="select">
                                  <option value=''><?php echo $this->_var['lang']['select_please']; ?></option>
                                    <?php $_from = $this->_var['districts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
                                      <option value="<?php echo $this->_var['region']['region_id']; ?>" <?php if ($this->_var['region']['region_id'] == $this->_var['shop_info']['district']): ?>selected<?php endif; ?>><?php echo $this->_var['region']['region_name']; ?></option>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_address']; ?>：</dt>
                            <dd>
                            	<input type="text" name="shop_address" value="<?php echo $this->_var['shop_info']['shop_address']; ?>" class="text"/>
                                &nbsp;&nbsp;<a href="javascript:;" target="_blank">注意：无需填写区域，格式如（中山北路3553号伸大厦）</a>
                            </dd>
                        </dl>
                        <dl class="hide"> 
                            <dt><?php echo $this->_var['lang']['tengxun_key']; ?>：</dt>
                            <dd>
                            	<input type="text" name="tengxun_key" value="<?php echo $this->_var['shop_info']['tengxun_key']; ?>" class="text" />&nbsp;&nbsp;<a href="http://lbs.qq.com/mykey.html" target="_blank">获取密钥</a>
                            </dd>
                        </dl>
                        <dl> 
                            <dt><?php echo $this->_var['lang']['longitude']; ?>：</dt>
                            <dd>
                            	<input type="text" name="longitude" value="<?php echo $this->_var['shop_info']['longitude']; ?>" class="text" />&nbsp;&nbsp;<a href="javascript:;" onclick="get_coordinate();">点击获取坐标</a>
                                <p class="bf100"><label  class="blue_label fl ml0"><?php echo $this->_var['lang']['longitude_desc']; ?></label></p>
                            </dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['latitude']; ?>：</dt>
                            <dd>
                            	<input type="text" name="latitude" value="<?php echo $this->_var['shop_info']['latitude']; ?>" class="text" />&nbsp;&nbsp;<a href="javascript:;" onclick="get_coordinate();">点击获取坐标</a>
                                <p class="bf100"><label  class="blue_label fl ml0"><?php echo $this->_var['lang']['latitude_desc']; ?></label></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['03_shipping_list']; ?>：</dt>
                            <dd>
                                <select name="shipping_id" id="shipping_id" class="select">
                                    <option value="0"><?php echo $this->_var['lang']['select_please']; ?></option>
                                    <?php $_from = $this->_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <?php if ($this->_var['priv_ru'] == 0 || ( $this->_var['priv_ru'] != 1 && $this->_var['list']['shipping_code'] != 'cac' )): ?>
                                    <option value="<?php echo $this->_var['list']['shipping_id']; ?>" <?php if ($this->_var['shop_info']['shipping_id'] == $this->_var['list']['shipping_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_var['list']['shipping_name']; ?></option>
                                    <?php endif; ?>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                 </select>								
                            </dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_mobile']; ?>：</dt>
                            <dd><input type="text" size="40" value="<?php echo $this->_var['shop_info']['mobile']; ?>" name="mobile" class="text text_2"></dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_address']; ?>：</dt>
                            <dd><input type="text" size="40" value="<?php echo $this->_var['shop_info']['seller_email']; ?>" name="seller_email" class="text text_2"></dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_qq']; ?>：</dt>
                            <dd>
                              <textarea name='kf_qq' value="<?php echo $this->_var['shop_info']['kf_qq']; ?>" rows="6" cols="48" class="textarea"><?php echo $this->_var['shop_info']['kf_qq']; ?></textarea>
                              <p class="bf100"><label  class="blue_label fl ml0"><?php echo $this->_var['lang']['kf_qq_prompt']; ?></label></p>
                            </dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_taobao']; ?>：</dt>
                            <dd>
                              <textarea name='kf_ww' value="<?php echo $this->_var['shop_info']['kf_ww']; ?>" rows="6" cols="48" class="textarea"><?php echo $this->_var['shop_info']['kf_ww']; ?></textarea>
                              <p class="bf100"><label  class="blue_label fl ml0"><?php echo $this->_var['lang']['kf_ww_prompt']; ?></label></p>
                            </dd>
                        </dl>
                            <!--
                            @author-bylu 在线客服 start
                            满足以下2种情况之一即显示"在线客服"的设置:
                            1.大商创平台;
                            2.设置了"在线客服"的商家;
                            -->
                        <?php if ($this->_var['shop_information']['is_IM'] == 1 || $this->_var['shop_information']['is_dsc']): ?>
                        <dl>
                            <dt>在线客服账号：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_touid']; ?>" name="kf_touid" class="text text_1">
                                <p class="bf100"><label  class="blue_label fl ml0">　在<a style="color: red;text-decoration: underline" target="_blank" href="http://my.open.taobao.com/app/app_list.htm">淘宝开放平台</a>已开通云旺客服的账号 。</label></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>在线客服appkey：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_appkey']; ?>" name="kf_appkey" class="text text_1">
                                <p class="bf100"><label  class="blue_label fl ml0">　在淘宝开放平台创建一个应用(百川无线)即可获得appkey。</label></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>在线客服secretkey：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_secretkey']; ?>" name="kf_secretkey" class="text text_1">
                                <p class="bf100"><label  class="blue_label fl ml0">　在淘宝开放平台创建一个应用(百川无线)即可获得secretkey。</label></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>在线客服头像LOGO：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_logo']; ?>" name="kf_logo" class="text text_1">
                                <p class="bf100"><label  class="blue_label fl ml0">　直接黏贴图片网址(推荐40 x 40),不填即使用默认头像。</label></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>在线客服欢迎信息：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_welcomeMsg']; ?>" name="kf_welcomeMsg" class="text text_1">
                                <p class="bf100"><label  class="blue_label fl ml0">　向用户发送的一条欢迎信息。</label></p>
                            </dd>
                        </dl>
                        <?php endif; ?>
                        <!--@author-bylu 在线客服 end-->
                        <dl>
                            <dt>美恰客服：</dt>
                            <dd>
                                <input type="text" size="40" value="<?php echo $this->_var['shop_info']['meiqia']; ?>" name="meiqia" class="text text_2">
                                <p class="bf100"><label  class="blue_label fl ml0">&nbsp;&nbsp;此功能仅手机端（wap）使用</label></p>
                            </dd>
                        </dl>	
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_tel']; ?>：</dt>
                            <dd><input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_tel']; ?>" name="kf_tel" class="text text_2"></dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['customer_service_css']; ?>：</dt>
                            <dd>
                                <div class="checkbox_items">
                                    <label class="mr10"><input name="kf_type" type="radio" value="0" class="checkbox" <?php if ($this->_var['shop_info']['kf_type'] == 0): ?>checked="checked"<?php endif; ?> /><?php echo $this->_var['lang']['QQ_kf']; ?></label>
                                    <label><input name="kf_type" type="radio" value="1" class="checkbox" <?php if ($this->_var['shop_info']['kf_type'] == 1): ?>checked="checked"<?php endif; ?> /><?php echo $this->_var['lang']['wangwang_kf']; ?></label>
                                </div>
                            </dd>
                        </dl>
                        
                        <?php if ($this->_var['priv_ru'] != 1): ?>
                        <dl>
                            <dt><?php echo $this->_var['lang']['seller_logo']; ?>：</dt>
                            <dd>
                                 <input type="file" name="shop_logo" class="file mt5 mb5"/><label class="blue_label">(无限制*128像素)</label><br />
                                 <?php if ($this->_var['shop_info']['shop_logo']): ?>
                                    <div class="seller_img"><img src="<?php echo $this->_var['shop_info']['shop_logo']; ?>" width="150" /></div>
                                 <?php endif; ?>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt><?php echo $this->_var['lang']['logo_sbt']; ?>：</dt>
                            <dd>
                                 <input type="file" name="logo_thumb" class="file mt5 mb5"/><label class="blue_label">(80*80像素)</label><br />
                                 <?php if ($this->_var['shop_info']['logo_thumb']): ?>
                                 <div class="seller_img"><img src="<?php echo $this->_var['shop_info']['logo_thumb']; ?>" width="80" height="80" /></div>
                                 <?php endif; ?>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_street_sbt']; ?>：</dt>
                            <dd>
                                 <input type="file" name="street_thumb" class="file mt5 mb5"/><label class="blue_label">(388*187像素)</label><br />
                                 <?php if ($this->_var['shop_info']['street_thumb']): ?>
                                 <div class="seller_img"><img src="../<?php echo $this->_var['shop_info']['street_thumb']; ?>" width="128" height="62" /></div>
                                 <?php endif; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_street_brand_sbt']; ?>：</dt>
                            <dd>
                                 <input type="file" name="brand_thumb" class="file mt5 mb5"/><label class="blue_label">(180*60像素)</label><br />
                                 <?php if ($this->_var['shop_info']['brand_thumb']): ?>
                                 <div class="seller_img"><img src="../<?php echo $this->_var['shop_info']['brand_thumb']; ?>" width="180" height="60" /></div>
                                 <?php endif; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>二维码中间Logo: </dt>
                            <dd>
                                 <input type="file" name="qrcode_thumb"/>
                                 <?php if ($this->_var['shop_info']['qrcode_thumb']): ?>
                                 <img src="<?php echo $this->_var['shop_info']['qrcode_thumb']; ?>" width="80" height="80" /> 
                                 <?php endif; ?>   
                                 (80*80像素)
                            </dd>
                        </dl>  
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_street_desc']; ?>：</dt>
                            <dd>
                                 <textarea name="street_desc" class="textarea"><?php echo $this->_var['shop_info']['street_desc']; ?></textarea>
                            </dd>
                        </dl>
                        <?php endif; ?>
                        <dl>
                            <dt><?php echo $this->_var['lang']['shop_notice']; ?>：</dt>
                            <dd><textarea name="notice" rows="10" cols="60"><?php echo $this->_var['shop_info']['notice']; ?></textarea></dd>
                        </dl>									
                        <div class="bottom mt20">
                        	<input type="hidden" name="data_op" value="<?php echo $this->_var['data_op']; ?>"/>
                            <label class="submit-border"><input type="submit" class="button" value="提交"></label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript">
<!--

region.isAdmin = true;

onload = function()
{
	if(document.getElementById('paynon')){
		document.getElementById('paynon').style.display = 'none';
	}
}

function validator()
{
  var validator = new Validator('theForm');
  validator.required('shop_name', shop_name_not_null);
  
  var shipping_id = document.getElementById('shipping_id').value;
  
  if(shipping_id == 0){
	  alert("请选择配送方式");
	  return false;
  }
  
  return validator.passed();
}

function show_shipping_area()
{
  Ajax.call('shipping.php?act=shipping_priv', '', shippingResponse, 'GET', 'JSON');
}

function shippingResponse(result)
{
  var shipping_name = document.getElementById('shipping_type');
  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    shipping_name.options[0].selected = true;
    return;
  }
  
  var area = document.getElementById('shipping_area');
  if(shipping_name.value == '')
  {
    area.style.display = 'none';
  }
  else
  {
    area.style.display = "block";
  }
}

/* 获取坐标 */
function get_coordinate(){
	
	var province = $(":input[name='shop_province']").val();
	var city = $(":input[name='shop_city']").val();
	var district = $(":input[name='shop_district']").val();
	var address = $(":input[name='shop_address']").val();
	
	Ajax.call('index.php?is_ajax=1&act=tengxun_coordinate', 'province=' + province + '&city=' + city + '&district=' + district + '&address=' + address, coordinateResponse, 'GET', 'JSON');
}

function coordinateResponse(result){
	if(result.error){
		alert(result.message);
		$(":input[name='longitude']").val('');
		$(":input[name='latitude']").val('');
	}else{
		alert("已获取坐标");
		$(":input[name='longitude']").val(result.lng);
		$(":input[name='latitude']").val(result.lat);
	}
}

function loadConfig()
{
  var payment = document.forms['theForm'].elements['payment'];
  var paymentConfig = document.getElementById('paymentConfig');
  if(payment.value == '')
  {
    paymentConfig.style.display = 'none';
    return;
  }
  else
  {
    paymentConfig.style.display = 'block';
  }
  if(document.getElementById('paynon')){
	  if(payment.value == 'alipay')
 	 {
	  document.getElementById('paynon').style.display = 'block';
	}
	else
	{
	  document.getElementById('paynon').style.display = 'none';
	}
  }
	
  var params = 'code=' + payment.value;

  Ajax.call('payment.php?is_ajax=1&act=get_config', params, showConfig, 'GET', 'JSON');
}

<?php if ($this->_var['is_false'] && $this->_var['priv_ru']): ?>
//Ajax.call('users.php?is_ajax=1&act=main_user','', start_user, 'GET', 'TEXT','FLASE');
function start_user(){
}
<?php endif; ?>
function showConfig(result)
{
  var payment = document.forms['theForm'].elements['payment'];
  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    payment.options[0].selected = true;
    return;
  }
  var paymentConfig = document.getElementById('paymentConfig');
  var config = result.content;

  paymentConfig.innerHTML = config;
}
<?php if ($this->_var['goods_false'] && $this->_var['priv_ru']): ?>
//Ajax.call('goods.php?is_ajax=1&act=main_dsc','', start_dsc, 'GET', 'TEXT','FLASE');
function start_dsc(){
	//
}
<?php endif; ?>

//-->
</script>
</body>
</html>
