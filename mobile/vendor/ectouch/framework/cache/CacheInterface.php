<?php
//dezend by  QQ:2172298892
namespace base\cache;

interface CacheInterface
{
	public function set($key, $value, $expire = 1800);

	public function des($key, $value = 1);

	public function del($key);

	public function clear();
}


?>
