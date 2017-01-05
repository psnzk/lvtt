<?php
//dezend by  QQ:2172298892
namespace libraries\sms;

class Alidayu
{
	/**
     * 短信类配置
     * @var array
     */
	protected $config = array('sms_name' => '', 'sms_password' => '');
	/**
     * @var objcet 短信对象
     */
	protected $sms;
	protected $sms_api = 'http://106.ihuyi.com/webservice/sms.php?method=Submit';
	protected $content = array();
	protected $phones = array();
	protected $errorInfo = '';

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
	}

	public function setSms($msg)
	{
		if (is_array($msg)) {
			if (empty($this->config['SmsCdoe'])) {
				$str_content = array('mobile_phone' => $this->phones, 'mobile_code' => $msg['mobile_code'], 'user_name' => $msg['user_name']);
				$this->content = $this->get_register_lang($str_content);
			}
			else {
				$str_content = array('mobile_phone' => $this->phones, 'mobile_code' => $msg['mobile_code'], 'user_name' => $msg['user_name']);
				$this->content = $this->get_register($str_content);
			}
		}

		return $this;
	}

	public function sendSms($to)
	{
		$sendTo = explode(',', $to);

		foreach ($sendTo as $add) {
			if (preg_match('/^0?1((3|7|8)[0-9]|5[0-35-9]|4[57])\\d{8}$/', $add)) {
				array_push($this->phones, $add);
			}
		}

		if ($this->phones && $this->content) {
			foreach ($this->phones as $phone) {
				return $this->send($phone);
			}
		}

		return false;
	}

	public function send($phone)
	{
		require_once __DIR__ . '/../aliyunyu/TopSdk.php';
		$c = new \TopClient();
		$c->appkey = $GLOBALS['_CFG']['ali_appkey'];
		$c->secretKey = $GLOBALS['_CFG']['ali_secretkey'];
		$c->format = 'json';
		$req = new \AlibabaAliqinFcSmsNumSendRequest();
		$req->setSmsType($this->content['SmsType']);
		$req->setSmsFreeSignName($this->content['SignName']);
		$req->setSmsParam($this->content['smsParams']);
		$req->setRecNum($phone);
		$req->setSmsTemplateCode($this->content['SmsCdoe']);
		$resp = $c->execute($req);

		if ($resp->code == 0) {
			return true;
		}
		else if ($resp->sub_msg) {
			$this->errorInfo = $resp->sub_msg;
		}
		else {
			$this->errorInfo = $resp->msg;
		}

		return false;
	}

	public function getError()
	{
		return $this->errorInfo;
	}

	public function get_register_lang($str_centent = array())
	{
		$smsParams = array('code' => $str_centent['mobile_code'], 'product' => $str_centent['user_name']);
		$result = array('SmsType' => 'normal', 'SignName' => $this->config['SignName'], 'SmsCdoe' => 'SMS_1000000', 'smsParams' => json_encode($smsParams), 'mobile_phone' => $str_centent['mobile_phone']);
		return $result;
	}

	public function get_register($str_centent = array())
	{
		$smsParams = array('code' => $str_centent['mobile_code'], 'product' => $str_centent['user_name']);
		$result = array('SmsType' => 'normal', 'SignName' => $this->config['SignName'], 'SmsCdoe' => $this->config['SmsCdoe'], 'smsParams' => json_encode($smsParams), 'mobile_phone' => $str_centent['mobile_phone']);
		return $result;
	}

	public function __destruct()
	{
		$this->sms = null;
	}
}


?>
