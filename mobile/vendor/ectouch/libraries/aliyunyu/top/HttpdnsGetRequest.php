<?php
//dezend by  QQ:2172298892
class HttpdnsGetRequest
{
	private $apiParas = array();

	public function getApiMethodName()
	{
		return 'taobao.httpdns.get';
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function check()
	{
	}

	public function putOtherTextParam($key, $value)
	{
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}


?>
