<?php
//dezend by  QQ:2172298892
class Express
{
	private $expressname = array();

	public function __construct()
	{
		$this->expressname = $this->expressname();
	}

	private function getcontent($url)
	{
		if (function_exists('file_get_contents')) {
			$file_contents = file_get_contents($url);
		}
		else {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}

		return $file_contents;
	}

	private function expressname()
	{
		$result = $this->getcontent('http://www.kuaidi100.com/');
		preg_match_all('/data\\-code\\="(?P<name>\\w+)"\\>\\<span\\>(?P<title>.*)\\<\\/span>/iU', $result, $data);
		$name = array();

		foreach ($data['title'] as $k => $v) {
			$name[$v] = $data['name'][$k];
		}

		return $name;
	}

	private function json_array($json)
	{
		if ($json) {
			foreach ((array) $json as $k => $v) {
				$data[$k] = !is_string($v) ? $this->json_array($v) : $v;
			}

			return $data;
		}
	}

	public function getorder($name, $order)
	{
		$keywords = $this->expressname[$name];
		$result = $this->getcontent('http://www.kuaidi100.com/query?type=' . $keywords . '&postid=' . $order);
		$result = json_decode($result);
		$data = $this->json_array($result);
		return $data;
	}
}


?>
