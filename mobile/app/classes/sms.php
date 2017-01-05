<?php
//dezend by  QQ:2172298892
namespace classes;

class sms
{
	/**
     * 存放提供远程服务的URL。
     *
     * @access  private
     * @var     array       $api_urls
     */
	public $api_urls = array('info' => 'http://api.sms.shopex.cn', 'send' => 'http://api.sms.shopex.cn', 'servertime' => 'http://webapi.sms.shopex.cn');
	/**
     * 存放MYSQL对象
     *
     * @access  private
     * @var     object      $db
     */
	public $db;
	/**
     * 存放ECS对象
     *
     * @access  private
     * @var     object      $ecs
     */
	public $ecs;
	/**
     * 存放transport对象
     *
     * @access  private
     * @var     object      $t
     */
	public $t;
	/**
     * 存放程序执行过程中的错误信息，这样做的一个好处是：程序可以支持多语言。
     * 程序在执行相关的操作时，error_no值将被改变，可能被赋为空或大等0的数字.
     * 为空或0表示动作成功；大于0的数字表示动作失败，该数字代表错误号。
     *
     * @access  public
     * @var     array       $errors
     */
	public $errors = array(
		'api_errors'    => array('error_no' => -1, 'error_msg' => ''),
		'server_errors' => array('error_no' => -1, 'error_msg' => '')
		);

	public function __construct()
	{
		$global = getinstance();
		$this->db = $global->db;
		$this->ecs = $global->ecs;
		$this->t = new transport(-1, -1, -1, false);
		$this->json = new JSON();
	}

	public function send($phones, $msg, $send_date = '', $send_num = 1, $sms_type = '', $version = '1.0')
	{
		$contents = $this->get_contents($phones, $msg);

		if (!$contents) {
			$this->errors['server_errors']['error_no'] = 3;
			return false;
		}

		$login_info = $this->getSmsInfo();

		if (!$login_info) {
			$this->errors['server_errors']['error_no'] = 5;
			return false;
		}
		else if ($login_info['info']['account_info']['active'] != '1') {
			$this->errors['server_errors']['error_no'] = 11;
			return false;
		}

		$sms_url = $this->get_url('send');

		if (!$sms_url) {
			$this->errors['server_errors']['error_no'] = 6;
			return false;
		}

		$t_contents = array();

		if (1 < count($contents)) {
			foreach ($contents as $key => $val) {
				$t_contents['0']['phones'] = $val['phones'];
				$t_contents['0']['content'] = $val['content'];
				$send_str['contents'] = $this->json->encode($t_contents);
				$send_str['certi_app'] = 'sms.send';
				$send_str['entId'] = c('ent_id');
				$send_str['entPwd'] = c('ent_ac');
				$send_str['source'] = SOURCE_ID;
				$send_str['sendType'] = 'fan-out';
				$send_str['use_backlist'] = '1';
				$send_str['version'] = $version;
				$send_str['format'] = 'json';
				$send_str['timestamp'] = $this->getTime();
				$send_str['certi_ac'] = $this->make_shopex_ac($send_str, SOURCE_TOKEN);
				$sms_url = $this->get_url('send');
				$arr = json_decode($send_str['contents'], true);
				$response = $this->t->request($sms_url, $send_str, 'POST');
				$result = $this->json->decode($response['body'], true);
				sleep(1);
			}
		}
		else {
			if (20 < strlen($contents['0']['phones'])) {
				$send_str['sendType'] = 'fan-out';
			}
			else {
				$send_str['sendType'] = 'notice';
			}

			$send_str['contents'] = $this->json->encode($contents);
			$send_str['certi_app'] = 'sms.send';
			$send_str['entId'] = c('ent_id');
			$send_str['entPwd'] = c('ent_ac');
			$send_str['license'] = '111111';
			$send_str['source'] = SOURCE_ID;
			$send_str['use_backlist'] = '1';
			$send_str['version'] = $version;
			$send_str['format'] = 'json';
			$send_str['timestamp'] = $this->getTime();
			$send_str['certi_ac'] = $this->make_shopex_ac($send_str, SOURCE_TOKEN);
			$sms_url = $this->get_url('send');
			$arr = json_decode($send_str['contents'], true);
			$response = $this->t->request($sms_url, $send_str, 'POST');
			$result = $this->json->decode($response['body'], true);
		}

		if ($result['res'] == 'succ') {
			return true;
		}
		else if ($result['res'] == 'fail') {
			return false;
		}
	}

	public function check_enable_info($email, $password)
	{
		if (empty($email) || empty($password)) {
			return false;
		}

		return true;
	}

	public function has_registered()
	{
		$sql = "SELECT `value`\r\n                FROM " . $this->ecs->table('shop_config') . "\r\n                WHERE `code` = 'ent_id'";
		$result = $this->db->getOne($sql);

		if (empty($result)) {
			return false;
		}

		return true;
	}

	public function get_site_info()
	{
		$email = $this->get_admin_email();
		$email = ($email ? $email : '');
		$domain = $this->ecs->get_domain();
		$domain = ($domain ? $domain : '');
		$sms_site_info['email'] = $email;
		$sms_site_info['domain'] = $domain;
		return $sms_site_info;
	}

	public function get_site_url()
	{
		$url = $this->ecs->url();
		$url = ($url ? $url : '');
		return $url;
	}

	public function get_admin_email()
	{
		$sql = 'SELECT `email` FROM ' . $this->ecs->table('admin_user') . ' WHERE `user_id` = \'' . $_SESSION['admin_id'] . '\'';
		$email = $this->db->getOne($sql);

		if (empty($email)) {
			return false;
		}

		return $email;
	}

	public function getSmsInfo($certi_app = 'sms.info', $version = '1.0', $format = 'json')
	{
		$send_str['certi_app'] = $certi_app;
		$send_str['entId'] = c('ent_id');
		$send_str['entPwd'] = c('ent_ac');
		$send_str['source'] = SOURCE_ID;
		$send_str['version'] = $version;
		$send_str['format'] = $format;
		$send_str['timestamp'] = $this->getTime();
		$send_str['certi_ac'] = $this->make_shopex_ac($send_str, SOURCE_TOKEN);
		$sms_url = $this->get_url('info');
		$response = $this->t->request($sms_url, $send_str, 'POST');
		$result = $this->json->decode($response['body'], true);

		if ($result['res'] == 'succ') {
			return $result;
		}
		else if ($result['res'] == 'fail') {
			return false;
		}
	}

	public function get_contents($phones, $msg)
	{
		if (empty($phones) || empty($msg)) {
			return false;
		}

		$msg .= c('default_sms_sign');
		$phone_key = 0;
		$i = 0;
		$phones = explode(',', $phones);

		foreach ($phones as $key => $value) {
			if ($i < 200) {
				$i++;
			}
			else {
				$i = 0;
				$phone_key++;
			}

			if ($this->is_moblie($value)) {
				$phone[$phone_key][] = $value;
			}
			else {
				$i--;
			}
		}

		if (!empty($phone)) {
			foreach ($phone as $phone_key => $val) {
				if (EC_CHARSET != 'utf-8') {
					$phone_array[$phone_key]['phones'] = implode(',', $val);
					$phone_array[$phone_key]['content'] = iconv('gb2312', 'utf-8', $msg);
				}
				else {
					$phone_array[$phone_key]['phones'] = implode(',', $val);
					$phone_array[$phone_key]['content'] = $msg;
				}
			}

			return $phone_array;
		}
		else {
			return false;
		}
	}

	public function getTime()
	{
		$Tsend_str['certi_app'] = 'sms.servertime';
		$Tsend_str['version'] = '1.0';
		$Tsend_str['format'] = 'json';
		$Tsend_str['certi_ac'] = $this->make_shopex_ac($Tsend_str, 'SMS_TIME');
		$sms_url = $this->get_url('servertime');
		$response = $this->t->request($sms_url, $Tsend_str, 'POST');
		$result = $this->json->decode($response['body'], true);
		return $result['info'];
	}

	public function get_url($key)
	{
		$url = $this->api_urls[$key];

		if (empty($url)) {
			return false;
		}

		return $url;
	}

	public function is_moblie($moblie)
	{
		return preg_match('/^0?1((3|8)[0-9]|5[0-35-9]|4[57])\\d{8}$/', $moblie);
	}

	public function make_shopex_ac($temp_arr, $token)
	{
		ksort($temp_arr);
		$str = '';

		foreach ($temp_arr as $key => $value) {
			if ($key != 'certi_ac') {
				$str .= $value;
			}
		}

		return strtolower(md5($str . strtolower(md5($token))));
	}

	public function base_encode($str)
	{
		$str = base64_encode($str);
		return strtr($str, $this->pattern());
	}

	public function pattern()
	{
		return array('+' => '_1_', '/' => '_2_', '=' => '_3_');
	}
}


?>
