<?php
//dezend by  QQ:2172298892
function safe_filter(&$arr)
{
	is_array($arr) && array_walk_recursive($arr, 'think_filter');

	if (is_array($arr)) {
		foreach ($arr as $key => $value) {
			if (!is_array($value)) {
				if (!get_magic_quotes_gpc()) {
					$value = addslashes($value);
				}

				$arr[$key] = htmlspecialchars($value, ENT_QUOTES);
			}
			else {
				safe_filter($arr[$key]);
			}
		}
	}
}

function think_filter(&$value)
{
	if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
		$value .= ' ';
	}
}

function C($key = NULL, $value = NULL)
{
	if (func_num_args() <= 1) {
		return \base\Config::get($key);
	}
	else {
		return \base\Config::set($key, $value);
	}
}

function U($route = NULL, $params = array(), $domain = false)
{
	$url = \base\Route::url($route, $params);

	if (true === $domain) {
		$domain = $_SERVER['HTTP_HOST'];
		$url = (is_ssl() ? 'https://' : 'http://') . $domain . $url;
	}

	return $url;
}

function A($class, $layer = 'models')
{
	static $objArr = array();
	$param = explode('/', $class, 2);
	$paramCount = count($param);

	switch ($paramCount) {
	case 1:
		$app = APP_NAME;
		$module = $param[0];
		break;

	case 2:
		$app = $param[0];
		$module = $param[1];
		break;
	}

	$app = strtolower($app);
	$class = '\\http\\' . $app . '\\' . $layer . '\\' . $module . ucfirst(rtrim($layer, 's'));

	if (!class_exists($class)) {
		$class = '\\http\\base\\' . $layer . '\\' . $module . ucfirst(rtrim($layer, 's'));
	}

	if (isset($objArr[$class])) {
		return $objArr[$class];
	}

	if (!class_exists($class)) {
		throw new Exception('Class \'' . $class . '\' not found\'', 500);
	}

	$obj = new $class();
	$objArr[$class] = $obj;
	return $obj;
}

function is_wechat_browser()
{
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if (strpos($user_agent, 'micromessenger') === false) {
		return false;
	}
	else {
		return true;
	}
}

function removeEmoji($nickname)
{
	$clean_text = '';
	$regexEmoticons = '/[\\x{1F600}-\\x{1F64F}]/u';
	$clean_text = preg_replace($regexEmoticons, '', $text);
	$regexSymbols = '/[\\x{1F300}-\\x{1F5FF}]/u';
	$clean_text = preg_replace($regexSymbols, '', $clean_text);
	$regexTransport = '/[\\x{1F680}-\\x{1F6FF}]/u';
	$clean_text = preg_replace($regexTransport, '', $clean_text);
	$regexMisc = '/[\\x{2600}-\\x{26FF}]/u';
	$clean_text = preg_replace($regexMisc, '', $clean_text);
	$regexDingbats = '/[\\x{2700}-\\x{27BF}]/u';
	$clean_text = preg_replace($regexDingbats, '', $clean_text);
	return $clean_text;
}

function http()
{
	return isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off') ? 'https://' : 'http://';
}

function get_domain()
{
	$protocol = http();

	if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	else if (isset($_SERVER['HTTP_HOST'])) {
		$host = $_SERVER['HTTP_HOST'];
	}
	else {
		if (isset($_SERVER['SERVER_PORT'])) {
			$port = ':' . $_SERVER['SERVER_PORT'];
			if (((':80' == $port) && ('http://' == $protocol)) || ((':443' == $port) && ('https://' == $protocol))) {
				$port = '';
			}
		}
		else {
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME'])) {
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		else if (isset($_SERVER['SERVER_ADDR'])) {
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}

function get_top_domain($url = '')
{
	$url = (empty($url) ? get_domain() : $url);
	$host = strtolower($url);

	if (strpos($host, '/') !== false) {
		$parse = @parse_url($host);
		$host = $parse['host'];
	}

	$topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
	$str = '';

	foreach ($topleveldomaindb as $v) {
		$str .= ($str ? '|' : '') . $v;
	}

	$matchstr = '[^\\.]+\\.(?:(' . $str . ')|\\w{2}|((' . $str . ')\\.\\w{2}))$';

	if (preg_match('/' . $matchstr . '/ies', $host, $matchs)) {
		$domain = $matchs['0'];
	}
	else {
		$domain = $host;
	}

	return $domain;
}

spl_autoload_register(function($class) {
	static $fileList = array();
	$prefixes = array('base' => BASE_PATH, 'libraries' => BASE_PATH, 'classes' => BASE_PATH, 'vendor' => BASE_PATH, 'apps' => BASE_PATH, '*' => BASE_PATH);
	$class = ltrim($class, '\\');

	if (false !== ($pos = strrpos($class, '\\'))) {
		$namespace = substr($class, 0, $pos);
		$className = substr($class, $pos + 1);

		foreach ($prefixes as $prefix => $baseDir) {
			if (('*' !== $prefix) && (0 !== strpos($namespace, $prefix))) {
				continue;
			}

			$fileDIR = $baseDir . str_replace('\\', '/', $namespace) . '/';

			if (!isset($fileList[$fileDIR])) {
				$fileList[$fileDIR] = array();
				$phpFile = glob($fileDIR . '*.php');

				if ($phpFile) {
					foreach ($phpFile as $file) {
						$fileList[$fileDIR][] = $file;
					}
				}
			}

			$fileBase = $baseDir . str_replace('\\', '/', $namespace) . '/' . $className;

			foreach ($fileList[$fileDIR] as $file) {
				if (false !== stripos($file, $fileBase)) {
					require $file;
					return true;
				}
			}
		}
	}

	return false;
});

?>
