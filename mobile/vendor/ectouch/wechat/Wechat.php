<?php
//dezend by  QQ:2172298892
namespace ectouch\wechat;

class Wechat extends SDK
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->cache = new \base\Cache();
	}

	public function log($log)
	{
		$log = (is_array($log) ? var_export($log, true) : $log);
		if ($this->debug && function_exists('logResult')) {
			logresult($log);
		}
	}

	public function clearCache()
	{
		return $this->cache->clear();
	}

	protected function setCache($cachename, $value, $expired)
	{
		return $this->cache->set($cachename, $value, $expired);
	}

	protected function getCache($cachename)
	{
		return $this->cache->get($cachename);
	}

	protected function removeCache($cachename)
	{
		return $this->cache->del($cachename);
	}
}

?>
