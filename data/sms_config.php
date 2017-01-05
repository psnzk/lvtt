<?php
//dezend by  QQ:2172298892
function get_seller_edit_info_lang($str_centent = array())
{
	$content = sprintf($GLOBALS['_LANG']['edit_seller_info'], $str_centent['seller_name'], $str_centent['seller_password'], $str_centent['current_admin_name'], $str_centent['edit_time']);
	$smsParams = array('shop_name' => '', 'user_name' => $str_centent['shop_name'], 'content' => $content);
	$result = array('SmsType' => 'normal', 'SignName' => '注册验证', 'SmsCdoe' => 'SMS_12811399', 'smsParams' => json_encode($smsParams), 'mobile_phone' => $str_centent['mobile_phone']);
	return $result;
}

function get_register_lang($str_centent = array())
{
	$smsParams = array('code' => $str_centent['mobile_code']);

	if ($str_centent['user_name']) {
		$smsParams['product'] = $str_centent['user_name'];
	}

	$result = array('SmsType' => 'normal', 'SignName' => '注册验证', 'SmsCdoe' => 'SMS_12465179', 'smsParams' => json_encode($smsParams), 'mobile_phone' => $str_centent['mobile_phone']);
	return $result;
}

function get_order_info_lang($str_centent = array())
{
	if ($str_centent['shop_name']) {
		$str_centent['shop_name'] = '【' . $str_centent['shop_name'] . '】';
	}

	$smsParams = array('shop_name' => $str_centent['shop_name'], 'user_name' => $str_centent['user_name'], 'content' => $str_centent['order_msg']);
	$result = array('SmsType' => 'normal', 'SignName' => '变更验证', 'SmsCdoe' => 'SMS_12826146', 'smsParams' => json_encode($smsParams), 'mobile_phone' => $str_centent['mobile_phone']);
	return $result;
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
