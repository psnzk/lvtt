<?php
//dezend by  QQ:2172298892
namespace libraries\sms;

class Ihuyi
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
	protected $content;
	protected $phones = array();
	protected $errorInfo;

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
	}

	public function setSms($content)
	{
		$this->content = $content;
		return $this;
	}

	public function sendSms($to)
	{
		$sendTo = explode(',', $to);

		foreach ($sendTo as $add) {
			if (preg_match('/^0?1((3|7|8)[0-9]|5[0-35-9]|4[57])\\d{8}$/', $add)) {
				$this->addPhone($add);
			}
		}

		if (!$this->send()) {
			$return = false;
		}
		else {
			$return = true;
		}

		return $return;
	}

	public function addPhone($add)
	{
		array_push($this->phones, $add);
	}

	public function send()
	{
		foreach ($this->phones as $mobile) {
			$post_data = array('account' => $this->config['sms_name'], 'password' => $this->config['sms_password'], 'mobile' => $mobile, 'content' => $this->content);
			$res = \libraries\Http::doPost($this->sms_api, $post_data);
			$data = $this->xmlToArray($res);

			if ($data['SubmitResult']['code'] == 2) {
				return true;
			}
			else {
				$this->errorInfo = $data['SubmitResult']['msg'];
				logresult(var_export($this->errorInfo, true));
				return false;
			}
		}
	}

	public function xmlToArray($xml)
	{
		$reg = '/<(\\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/';

		if (preg_match_all($reg, $xml, $matches)) {
			$count = count($matches[0]);

			for ($i = 0; $i < $count; $i++) {
				$subxml = $matches[2][$i];
				$key = $matches[1][$i];

				if (preg_match($reg, $subxml)) {
					$arr[$key] = $this->xmlToArray($subxml);
				}
				else {
					$arr[$key] = $subxml;
				}
			}
		}

		return $arr;
	}

	public function getError()
	{
		return $this->errorInfo;
	}

	public function __destruct()
	{
		$this->sms = null;
	}
}


?>
