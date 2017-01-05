<?php
//dezend by  QQ:2172298892
function getInstance()
{
	static $obj = array();

	if (empty($obj)) {
		$obj = a('base/Frontend', 'controllers');
	}

	return $obj;
}

function check_verify($code, $id = '')
{
	$verify = new \ectouch\verify\Verify();
	return $verify->check($code, $id);
}

function ectouch_global_assets($type = 'css')
{
	$assets = c('ASSETS');
	$gulps = array('dist' => 'statics/');

	if (APP_DEBUG) {
		$prefix = __ROOT__;
		$paths = array();

		foreach ($assets as $key => $item) {
			foreach ($item as $vo) {
				$res_url = ($key == 'public' ? $prefix . 'resources/assets/' : $prefix . $gulps['dist']);

				if (substr($vo, -3) == '.js') {
					$paths['js'][] = '<script src="' . $res_url . $vo . '"></script>';
					$gulps['js'][] = str_replace($prefix, '', $res_url) . $vo;
				}
				else if (substr($vo, -4) == '.css') {
					$paths['css'][] = '<link href="' . $res_url . $vo . '" rel="stylesheet" type="text/css" />';
					$gulps['css'][] = str_replace($prefix, '', $res_url) . $vo;
				}
			}
		}

		file_put_contents(ROOT_PATH . 'gulpconf.js', 'module.exports = ' . json_encode($gulps));
	}
	else {
		$prefix = __ROOT__ . $gulps['dist'];
		$paths = array(
			'css' => array('<link href="' . $prefix . 'css/app.min.css" rel="stylesheet" type="text/css" />'),
			'js'  => array('<script src="' . $prefix . 'js/app.min.js"></script>')
			);
	}

	return isset($paths[$type]) ? implode("\n", $paths[$type]) . "\n" : '';
}

function dump($var, $echo = true, $label = NULL, $strict = true)
{
	$label = ($label === NULL ? '' : rtrim($label) . ' ');

	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
		else {
			$output = $label . print_r($var, true);
		}
	}
	else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();

		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\\]\\=\\>\\n(\\s+)/m', '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}

	if ($echo) {
		echo $output;
		return NULL;
	}
	else {
		return $output;
	}
}

function filter($name, &$content)
{
	$class = $name . 'Filter';
	require_cache(APP_PATH . 'filters/' . $class . '.php');
	$filter = new $class();
	$content = $filter->run($content);
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']) && (('1' == $_SERVER['HTTPS']) || ('on' == strtolower($_SERVER['HTTPS'])))) {
		return true;
	}
	else {
		if (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
			return true;
		}
	}

	return false;
}

function redirect($url, $time = 0, $msg = '')
{
	$url = str_replace(array("\n", "\r"), '', $url);

	if (empty($msg)) {
		$msg = '系统将在' . $time . '秒之后自动跳转到' . $url . '！';
	}

	if (!headers_sent()) {
		if (0 === $time) {
			header('Location: ' . $url);
		}
		else {
			header('refresh:' . $time . ';url=' . $url);
			echo $msg;
		}

		exit();
	}
	else {
		$str = '<meta http-equiv=\'Refresh\' content=\'' . $time . ';URL=' . $url . '\'>';

		if ($time != 0) {
			$str .= $msg;
		}

		exit($str);
	}
}

function S($name, $value = '', $options = NULL)
{
	static $cache = '';
	if (is_array($options) && empty($cache)) {
		$type = (isset($options['type']) ? $options['type'] : '');
		$cache = Cache::getInstance($type, $options);
	}
	else if (is_array($name)) {
		$type = (isset($name['type']) ? $name['type'] : '');
		$cache = Cache::getInstance($type, $name);
		return $cache;
	}
	else if (empty($cache)) {
		$cache = Cache::getInstance();
	}

	if ('' === $value) {
		return $cache->get($name);
	}
	else if (is_null($value)) {
		return $cache->rm($name);
	}
	else {
		if (is_array($options)) {
			$expire = (isset($options['expire']) ? $options['expire'] : NULL);
		}
		else {
			$expire = (is_numeric($options) ? $options : NULL);
		}

		return $cache->set($name, $value, $expire);
	}
}

function F($name, $value = '', $path = DATA_PATH)
{
	static $_cache = array();
	$filename = $path . $name . '.php';

	if ('' !== $value) {
		if (is_null($value)) {
			return false !== strpos($name, '*') ? array_map('unlink', glob($filename)) : unlink($filename);
		}
		else {
			$dir = dirname($filename);

			if (!is_dir($dir)) {
				mkdir($dir, 493, true);
			}

			$_cache[$name] = $value;
			return file_put_contents($filename, strip_whitespace('<?php	return ' . var_export($value, true) . ';?>'));
		}
	}

	if (isset($_cache[$name])) {
		return $_cache[$name];
	}

	if (is_file($filename)) {
		$value = include $filename;
		$_cache[$name] = $value;
	}
	else {
		$value = false;
	}

	return $value;
}

function to_guid_string($mix)
{
	if (is_object($mix) && function_exists('spl_object_hash')) {
		return spl_object_hash($mix);
	}
	else if (is_resource($mix)) {
		$mix = get_resource_type($mix) . strval($mix);
	}
	else {
		$mix = serialize($mix);
	}

	return md5($mix);
}

function xml_encode($data, $root = 'think', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
{
	if (is_array($attr)) {
		$_attr = array();

		foreach ($attr as $key => $value) {
			$_attr[] = $key . '="' . $value . '"';
		}

		$attr = implode(' ', $_attr);
	}

	$attr = trim($attr);
	$attr = (empty($attr) ? '' : ' ' . $attr);
	$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
	$xml .= '<' . $root . $attr . '>';
	$xml .= data_to_xml($data, $item, $id);
	$xml .= '</' . $root . '>';
	return $xml;
}

function data_to_xml($data, $item = 'item', $id = 'id')
{
	$xml = $attr = '';

	foreach ($data as $key => $val) {
		if (is_numeric($key)) {
			$id && ($attr = ' ' . $id . '="' . $key . '"');
			$key = $item;
		}

		$xml .= '<' . $key . $attr . '>';
		$xml .= (is_array($val) || is_object($val) ? data_to_xml($val, $item, $id) : $val);
		$xml .= '</' . $key . '>';
	}

	return $xml;
}

function session($name, $value = '')
{
	$prefix = c('SESSION_PREFIX');

	if (is_array($name)) {
		if (isset($name['prefix'])) {
			c('SESSION_PREFIX', $name['prefix']);
		}

		if (c('VAR_SESSION_ID') && isset($_REQUEST[c('VAR_SESSION_ID')])) {
			session_id($_REQUEST[c('VAR_SESSION_ID')]);
		}
		else if (isset($name['id'])) {
			session_id($name['id']);
		}

		ini_set('session.auto_start', 0);

		if (isset($name['name'])) {
			session_name($name['name']);
		}

		if (isset($name['path'])) {
			session_save_path($name['path']);
		}

		if (isset($name['domain'])) {
			ini_set('session.cookie_domain', $name['domain']);
		}

		if (isset($name['expire'])) {
			ini_set('session.gc_maxlifetime', $name['expire']);
		}

		if (isset($name['use_trans_sid'])) {
			ini_set('session.use_trans_sid', $name['use_trans_sid'] ? 1 : 0);
		}

		if (isset($name['use_cookies'])) {
			ini_set('session.use_cookies', $name['use_cookies'] ? 1 : 0);
		}

		if (isset($name['cache_limiter'])) {
			session_cache_limiter($name['cache_limiter']);
		}

		if (isset($name['cache_expire'])) {
			session_cache_expire($name['cache_expire']);
		}

		if (isset($name['type'])) {
			c('SESSION_TYPE', $name['type']);
		}

		if (c('SESSION_TYPE')) {
			$class = 'Session' . ucwords(strtolower(c('SESSION_TYPE')));

			if (require_cache(EXTEND_PATH . 'Driver/Session/' . $class . '.class.php')) {
				$hander = new $class();
				$hander->execute();
			}
			else {
				throw_exception(l('_CLASS_NOT_EXIST_') . ': ' . $class);
			}
		}

		if (c('SESSION_AUTO_START')) {
			session_start();
		}
	}
	else if ('' === $value) {
		if (0 === strpos($name, '[')) {
			if ('[pause]' == $name) {
				session_write_close();
			}
			else if ('[start]' == $name) {
				session_start();
			}
			else if ('[destroy]' == $name) {
				$_SESSION = array();
				session_unset();
				session_destroy();
			}
			else if ('[regenerate]' == $name) {
				session_regenerate_id();
			}
		}
		else if (0 === strpos($name, '?')) {
			$name = substr($name, 1);

			if (strpos($name, '.')) {
				list($name1, $name2) = explode('.', $name);
				return $prefix ? isset($_SESSION[$prefix][$name1][$name2]) : isset($_SESSION[$name1][$name2]);
			}
			else {
				return $prefix ? isset($_SESSION[$prefix][$name]) : isset($_SESSION[$name]);
			}
		}
		else if (is_null($name)) {
			if ($prefix) {
				unset($_SESSION[$prefix]);
			}
			else {
				$_SESSION = array();
			}
		}
		else if ($prefix) {
			if (strpos($name, '.')) {
				list($name1, $name2) = explode('.', $name);
				return isset($_SESSION[$prefix][$name1][$name2]) ? $_SESSION[$prefix][$name1][$name2] : NULL;
			}
			else {
				return isset($_SESSION[$prefix][$name]) ? $_SESSION[$prefix][$name] : NULL;
			}
		}
		else if (strpos($name, '.')) {
			list($name1, $name2) = explode('.', $name);
			return isset($_SESSION[$name1][$name2]) ? $_SESSION[$name1][$name2] : NULL;
		}
		else {
			return isset($_SESSION[$name]) ? $_SESSION[$name] : NULL;
		}
	}
	else if (is_null($value)) {
		if ($prefix) {
			unset($_SESSION[$prefix][$name]);
		}
		else {
			unset($_SESSION[$name]);
		}
	}
	else if ($prefix) {
		if (!is_array($_SESSION[$prefix])) {
			$_SESSION[$prefix] = array();
		}

		$_SESSION[$prefix][$name] = $value;
	}
	else {
		$_SESSION[$name] = $value;
	}
}

function cookie($name, $value = '', $option = NULL)
{
	$config = array('prefix' => c('COOKIE_PREFIX'), 'expire' => c('COOKIE_EXPIRE'), 'path' => c('COOKIE_PATH'), 'domain' => c('COOKIE_DOMAIN'));

	if (!is_null($option)) {
		if (is_numeric($option)) {
			$option = array('expire' => $option);
		}
		else if (is_string($option)) {
			parse_str($option, $option);
		}

		$config = array_merge($config, array_change_key_case($option));
	}

	if (is_null($name)) {
		if (empty($_COOKIE)) {
			return NULL;
		}

		$prefix = (empty($value) ? $config['prefix'] : $value);

		if (!empty($prefix)) {
			foreach ($_COOKIE as $key => $val) {
				if (0 === stripos($key, $prefix)) {
					setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
					unset($_COOKIE[$key]);
				}
			}
		}

		return NULL;
	}

	$name = $config['prefix'] . $name;

	if ('' === $value) {
		if (isset($_COOKIE[$name])) {
			$value = $_COOKIE[$name];

			if (0 === strpos($value, 'think:')) {
				$value = substr($value, 6);
				return array_map('urldecode', json_decode(MAGIC_QUOTES_GPC ? stripslashes($value) : $value, true));
			}
			else {
				return $value;
			}
		}
		else {
			return NULL;
		}
	}
	else if (is_null($value)) {
		setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
		unset($_COOKIE[$name]);
	}
	else {
		if (is_array($value)) {
			$value = 'think:' . json_encode(array_map('urlencode', $value));
		}

		$expire = (!empty($config['expire']) ? time() + intval($config['expire']) : 0);
		setcookie($name, $value, $expire, $config['path'], $config['domain']);
		$_COOKIE[$name] = $value;
	}
}

function get_client_ip($type = 0)
{
	$type = ($type ? 1 : 0);
	static $ip;

	if ($ip !== NULL) {
		return $ip[$type];
	}

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown', $arr);

		if (false !== $pos) {
			unset($arr[$pos]);
		}

		$ip = trim($arr[0]);
	}
	else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	$long = sprintf('%u', ip2long($ip));
	$ip = ($long ? array($ip, $long) : array('0.0.0.0', 0));
	return $ip[$type];
}

function send_http_status($code)
{
	static $_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Moved Temporarily ', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported', 509 => 'Bandwidth Limit Exceeded');

	if (isset($_status[$code])) {
		header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
		header('Status:' . $code . ' ' . $_status[$code]);
	}
}

function touch_filter(&$value)
{
	if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|LIKE|NOTLIKE|NOTBETWEEN|NOT BETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
		$value .= ' ';
	}
}

function I($name, $default = '', $filter = NULL)
{
	if (strpos($name, '.')) {
		list($method, $name) = explode('.', $name, 2);
	}
	else {
		$method = 'param';
	}

	switch (strtolower($method)) {
	case 'get':
		$input = &$_GET;
		break;

	case 'post':
		$input = &$_POST;
		break;

	case 'put':
		parse_str(file_get_contents('php://input'), $input);
		break;

	case 'param':
		switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			$input = $_POST;
			break;

		case 'PUT':
			parse_str(file_get_contents('php://input'), $input);
			break;

		default:
			$input = $_GET;
		}

		if (c('VAR_URL_PARAMS') && isset($_GET[c('VAR_URL_PARAMS')])) {
			$input = array_merge($input, $_GET[c('VAR_URL_PARAMS')]);
		}

		break;

	case 'request':
		$input = &$_REQUEST;
		break;

	case 'session':
		$input = &$_SESSION;
		break;

	case 'cookie':
		$input = &$_COOKIE;
		break;

	case 'server':
		$input = &$_SERVER;
		break;

	case 'globals':
		$input = &$GLOBALS;
		break;

	default:
		return NULL;
	}

	if (c('VAR_FILTERS')) {
		$_filters = explode(',', c('VAR_FILTERS'));

		foreach ($_filters as $_filter) {
			array_walk_recursive($input, $_filter);
		}
	}

	if (empty($name)) {
		$data = $input;
		$filters = (isset($filter) ? $filter : c('DEFAULT_FILTER'));

		if ($filters) {
			$filters = explode(',', $filters);

			foreach ($filters as $filter) {
				$data = array_map($filter, $data);
			}
		}
	}
	else if (isset($input[$name])) {
		$data = $input[$name];
		$filters = (isset($filter) ? $filter : c('DEFAULT_FILTER'));

		if ($filters) {
			$filters = explode(',', $filters);

			foreach ($filters as $filter) {
				if (function_exists($filter)) {
					$data = (is_array($data) ? array_map_recursive($filter, $data) : $filter($data));
				}
				else {
					$data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));

					if (false === $data) {
						return isset($default) ? $default : NULL;
					}
				}
			}
		}
	}
	else {
		$data = (isset($default) ? $default : NULL);
	}

	is_array($data) && array_walk_recursive($data, 'touch_filter');
	return $data;
}

function array_map_recursive($filter, $data)
{
	$result = array();

	foreach ($data as $key => $val) {
		$result[$key] = is_array($val) ? array_map_recursive($filter, $val) : call_user_func($filter, $val);
	}

	return $result;
}

function G($start, $end = '', $dec = 4)
{
	static $_info = array();
	static $_mem = array();

	if (is_float($end)) {
		$_info[$start] = $end;
	}
	else if (!empty($end)) {
		if (!isset($_info[$end])) {
			$_info[$end] = microtime(true);
		}

		if (MEMORY_LIMIT_ON && ($dec == 'm')) {
			if (!isset($_mem[$end])) {
				$_mem[$end] = memory_get_usage();
			}

			return number_format(($_mem[$end] - $_mem[$start]) / 1024);
		}
		else {
			return number_format($_info[$end] - $_info[$start], $dec);
		}
	}
	else {
		$_info[$start] = microtime(true);

		if (MEMORY_LIMIT_ON) {
			$_mem[$start] = memory_get_usage();
		}
	}
}

function N($key, $step = 0, $save = false)
{
	static $_num = array();

	if (!isset($_num[$key])) {
		$_num[$key] = false !== $save ? s('N_' . $key) : 0;
	}

	if (empty($step)) {
		return $_num[$key];
	}
	else {
		$_num[$key] = $_num[$key] + (int) $step;
	}

	if (false !== $save) {
		s('N_' . $key, $_num[$key], $save);
	}
}

function parse_name($name, $type = 0)
{
	if ($type) {
		return ucfirst(preg_replace('/_([a-zA-Z])/e', 'strtoupper(\'\\1\')', $name));
	}
	else {
		return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
	}
}

function require_cache($filename)
{
	static $_importFiles = array();

	if (!isset($_importFiles[$filename])) {
		if (file_exists_case($filename)) {
			require $filename;
			$_importFiles[$filename] = true;
		}
		else {
			$_importFiles[$filename] = false;
		}
	}

	return $_importFiles[$filename];
}

function require_array($array, $return = false)
{
	foreach ($array as $file) {
		if (require_cache($file) && $return) {
			return true;
		}
	}

	if ($return) {
		return false;
	}
}

function file_exists_case($filename)
{
	if (is_file($filename)) {
		if (IS_WIN && c('APP_FILE_CASE')) {
			if (basename(realpath($filename)) != basename($filename)) {
				return false;
			}
		}

		return true;
	}

	return false;
}

function M($name = '', $tablePrefix = '', $connection = '')
{
	static $_model = array();

	if (strpos($name, ':')) {
		list($class, $name) = explode(':', $name);
	}
	else {
		$class = 'Model';
	}

	$guid = $tablePrefix . $name . '_' . $class;

	if (!isset($_model[$guid])) {
		$_model[$guid] = new $class($name, $tablePrefix, $connection);
	}

	return $_model[$guid];
}

function L($name = NULL, $value = NULL)
{
	static $_lang = array();

	if (empty($name)) {
		return $_lang;
	}

	if (is_string($name)) {
		$name = strtoupper($name);

		if (is_null($value)) {
			return isset($_lang[$name]) ? $_lang[$name] : $name;
		}

		$_lang[$name] = $value;
		return NULL;
	}

	if (is_array($name)) {
		$_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));
	}

	return NULL;
}

function array_define($array, $check = true)
{
	$content = "\n";

	foreach ($array as $key => $val) {
		$key = strtoupper($key);

		if ($check) {
			$content .= 'defined(\'' . $key . '\') or ';
		}

		if (is_int($val) || is_float($val)) {
			$content .= 'define(\'' . $key . '\',' . $val . ');';
		}
		else if (is_bool($val)) {
			$val = ($val ? 'true' : 'false');
			$content .= 'define(\'' . $key . '\',' . $val . ');';
		}
		else if (is_string($val)) {
			$content .= 'define(\'' . $key . '\',\'' . addslashes($val) . '\');';
		}

		$content .= "\n";
	}

	return $content;
}

function request($str, $default = NULL, $function = NULL)
{
	$str = trim($str);
	list($method, $name) = explode('.', $str, 2);
	$method = strtoupper($method);

	switch ($method) {
	case 'POST':
		$type = $_POST;
		break;

	case 'SESSION':
		$type = $_SESSION;
		break;

	case 'REQUEST':
		$type = $_REQUEST;
		break;

	case 'COOKIE':
		$type = $_COOKIE;
		break;

	case 'GET':
	default:
		$type = $_GET;
		break;
	}

	if (empty($name)) {
		$request = filter_string($type);
	}
	else {
		if ($method == 'GET') {
			$request = urldecode($type[$name]);
		}
		else {
			$request = $type[$name];
		}

		$request = filter_string($request);

		if ($default) {
			if (empty($request)) {
				$request = $default;
			}
		}

		if ($function) {
			$request = call_user_func($function, $request);
		}
	}

	return $request;
}

function filter_string($data)
{
	if ($data === NULL) {
		return false;
	}

	if (is_array($data)) {
		foreach ($data as $k => $v) {
			$data[$k] = filter_string($v);
		}

		return $data;
	}
	else {
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}
}

function data_auth_sign($data)
{
	if (!is_array($data)) {
		$data = (array) $data;
	}

	ksort($data);
	$code = http_build_query($data);
	$sign = sha1($code);
	return $sign;
}

function load_config_file($file)
{
	$file = get_config_file($file);
	return require $file;
}

function current_config()
{
	return load_config('config');
}

function save_config($file, $config)
{
	if (empty($config) || !is_array($config)) {
		return array();
	}

	$file = get_config_file($file);
	$conf = file_get_contents($file);

	foreach ($config as $key => $value) {
		if (is_string($value) && !in_array($value, array('true', 'false'))) {
			if (!is_numeric($value)) {
				$value = '\'' . $value . '\'';
			}
		}

		$conf = preg_replace('/\'' . $key . '\'\\s*=\\>\\s*(.*?),/iU', '\'' . $key . '\'=>' . $value . ',', $conf);
	}

	if (!IS_WRITE) {
		return false;
	}
	else {
		if (file_put_contents($file, $conf)) {
			return true;
		}
		else {
			return false;
		}

		return '';
	}
}

function get_method_array($str = '')
{
	$strArray = array();

	if (!empty($str)) {
		$strArray = explode('/', $str, 3);
	}

	$strCount = count($strArray);

	switch ($strCount) {
	case 1:
		$app = APP_NAME;
		$controller = CONTROLLER_NAME;
		$action = $strArray[0];
		break;

	case 2:
		$app = APP_NAME;
		$controller = $strArray[0];
		$action = $strArray[1];
		break;

	case 3:
		$app = $strArray[0];
		$controller = $strArray[1];
		$action = $strArray[2];
		break;

	default:
		$app = APP_NAME;
		$controller = CONTROLLER_NAME;
		$action = ACTION_NAME;
		break;
	}

	return array('app' => strtolower($app), 'controller' => $controller, 'action' => $action);
}

function get_config_file($file)
{
	$name = $file;

	if (!is_file($file)) {
		$str = explode('/', $file);
		$strCount = count($str);

		switch ($strCount) {
		case 1:
			$app = APP_NAME;
			$name = $str[0];
			break;

		case 2:
			$app = $str[0];
			$name = $str[1];
			break;
		}

		$app = strtolower($app);
		if (empty($app) && empty($file)) {
			throw new Exception('Config \'' . $file . '\' not found\'', 500);
		}

		$file = APP_PATH . $app . '/conf/' . $name . '.php';

		if (!file_exists($file)) {
			throw new Exception('Config \'' . $file . '\' not found', 500);
		}
	}

	return $file;
}

function match_url($str, $params = array(), $mustParams = array())
{
	$newParams = array();
	$keyArray = array_keys($params);

	if (config('REWRITE_ON')) {
		$config = config('REWRITE_RULE');
		$configArray = array_flip($config);
		$route = $configArray[$str];

		if ($route) {
			preg_match_all('/<(\\w+)>/', $route, $matches);

			foreach ($matches[1] as $value) {
				if ($params[$value]) {
					$newParams[$value] = $params[$value];
				}
			}
		}
		else if (!empty($keyArray)) {
			$newParams[$keyArray[0]] = current($params);
		}
	}
	else if (!empty($keyArray)) {
		$newParams[$keyArray[0]] = current($params);
	}

	$newParams = array_merge((array) $newParams, (array) $mustParams);
	$newParams = array_filter($newParams);
	return url($str, $newParams);
}

function target($str, $layer = 'model')
{
	static $_target = array();
	$str = explode('/', $str);
	$strCount = count($str);

	switch ($strCount) {
	case 1:
		$app = APP_NAME;
		$module = $str[0];
		break;

	case 2:
		$app = $str[0];
		$module = $str[1];
		break;
	}

	$app = strtolower($app);
	$name = $app . '/' . $layer . '/' . $module;

	if (isset($_target[$name])) {
		return $_target[$name];
	}

	$class = '\\app\\' . $app . '\\' . $layer . '\\' . $module . ucfirst($layer);

	if (!class_exists($class)) {
		throw new Exception('Class \'' . $class . '\' not found\'', 500);
	}

	$target = new $class();
	$_target[$name] = $target;
	return $target;
}

function get_all_service($name, $method, $vars = array())
{
	if (empty($name)) {
		return NULL;
	}

	$apiPath = APP_PATH . '*/service/' . $name . 'Service.php';
	$apiList = glob($apiPath);

	if (empty($apiList)) {
		return NULL;
	}

	$appPathStr = strlen(APP_PATH);
	$method = 'get' . $method . $name;
	$data = array();

	foreach ($apiList as $value) {
		$path = substr($value, $appPathStr, -4);
		$path = str_replace('\\', '/', $path);
		$appName = explode('/', $path);
		$appName = $appName[0];
		$config = load_config($appName . '/config');
		if (!$config['APP_SYSTEM'] && (!$config['APP_STATE'] || !$config['APP_INSTALL'])) {
			continue;
		}

		$class = target($appName . '/' . $name, 'service');

		if (method_exists($class, $method)) {
			$data[$appName] = $class->$method($vars);
		}
	}

	return $data;
}

function service($appName, $name, $method, $vars = array())
{
	$config = load_config($appName . '/config');
	if (!$config['APP_SYSTEM'] && (!$config['APP_STATE'] || !$config['APP_INSTALL'])) {
		return NULL;
	}

	$class = target($appName . '/' . $name, 'service');

	if (method_exists($class, $method)) {
		return $class->$method($vars);
	}
}

function api($appName, $name, $method, $vars = array())
{
	$config = load_config($appName . '/config');
	if (!$config['APP_SYSTEM'] && (!$config['APP_STATE'] || !$config['APP_INSTALL'])) {
		return NULL;
	}

	$class = target($appName . '/' . $name, 'api');

	if (method_exists($class, $method)) {
		return $class->$method($vars);
	}
}

function array_order($array, $key, $type = 'asc', $reset = false)
{
	if (empty($array) || !is_array($array)) {
		return $array;
	}

	foreach ($array as $k => $v) {
		$keysvalue[$k] = $v[$key];
	}

	if ($type == 'asc') {
		asort($keysvalue);
	}
	else {
		arsort($keysvalue);
	}

	$i = 0;

	foreach ($keysvalue as $k => $v) {
		$i++;

		if ($reset) {
			$new_array[$k] = $array[$k];
		}
		else {
			$new_array[$i] = $array[$k];
		}
	}

	return $new_array;
}

function default_data($data, $var)
{
	if (empty($data)) {
		return $var;
	}
	else {
		return $data;
	}
}

function cut_image($img, $width, $height, $type = 3)
{
	if (empty($width) && empty($height)) {
		return $img;
	}

	$imgDir = realpath(ROOT_PATH . $img);

	if (!is_file($imgDir)) {
		return $img;
	}

	$imgInfo = pathinfo($img);
	$newImg = $imgInfo['dirname'] . '/cut_' . $width . '_' . $height . '_' . $imgInfo['basename'];
	$newImgDir = ROOT_PATH . $newImg;

	if (!is_file($newImgDir)) {
		$image = new \app\base\util\ThinkImage();
		$image->open($imgDir);
		$image->thumb($width, $height, $type)->save($newImgDir);
	}

	return $newImg;
}

function dir_size($directoty)
{
	$dir_size = 0;

	if ($dir_handle = @opendir($directoty)) {
		while ($filename = readdir($dir_handle)) {
			$subFile = $directoty . DIRECTORY_SEPARATOR . $filename;
			if (($filename == '.') || ($filename == '..')) {
				continue;
			}
			else if (is_dir($subFile)) {
				$dir_size += dir_size($subFile);
			}
			else if (is_file($subFile)) {
				$dir_size += filesize($subFile);
			}
		}

		closedir($dir_handle);
	}

	return $dir_size;
}

function copy_dir($sourceDir, $aimDir)
{
	$succeed = true;

	if (!file_exists($aimDir)) {
		if (!mkdir($aimDir, 511)) {
			return false;
		}
	}

	$objDir = opendir($sourceDir);

	while (false !== ($fileName = readdir($objDir))) {
		if (($fileName != '.') && ($fileName != '..')) {
			if (!is_dir($sourceDir . '/' . $fileName)) {
				if (!copy($sourceDir . '/' . $fileName, $aimDir . '/' . $fileName)) {
					$succeed = false;
					break;
				}
			}
			else {
				copy_dir($sourceDir . '/' . $fileName, $aimDir . '/' . $fileName);
			}
		}
	}

	closedir($objDir);
	return $succeed;
}

function del_dir($dir)
{
	\framework\ext\Util::delDir($dir);
}

function html_in($str)
{
	$str = htmlspecialchars($str);

	if (!get_magic_quotes_gpc()) {
		$str = addslashes($str);
	}

	return $str;
}

function html_out($str)
{
	if (function_exists('htmlspecialchars_decode')) {
		$str = htmlspecialchars_decode($str);
	}
	else {
		$str = html_entity_decode($str);
	}

	$str = stripslashes($str);
	return $str;
}

function len($str, $len = 0)
{
	if (!empty($len)) {
		return \framework\ext\Util::msubstr($str, 0, $len);
	}
	else {
		return $str;
	}
}

function unique_number()
{
	return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

function random_str()
{
	$year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
	$order_sn = $year_code[intval(date('Y')) - 2010] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
	return $order_sn;
}

function is_empty($str)
{
	if (empty($str)) {
		return false;
	}
	else {
		return true;
	}
}

function get_text_make($data, $cut = 0, $str = '...')
{
	$data = strip_tags($data);
	$pattern = '/&[a-zA-Z]+;/';
	$data = preg_replace($pattern, '', $data);

	if (!is_numeric($cut)) {
		return $data;
	}

	if (0 < $cut) {
		$data = mb_strimwidth($data, 0, $cut, $str);
	}

	return $data;
}

function logResult($word = '')
{
	$word = (is_array($word) ? var_export($word, true) : $word);
	$fp = fopen(ROOT_PATH . 'storage/logs/log.txt', 'a');
	flock($fp, LOCK_EX);
	fwrite($fp, '执行日期：' . date('Y-m-d H:i:s', time()) . "\n" . $word . "\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

function debug($data, $debug = true)
{
	if ($debug) {
		if (isset($_GET['debug'])) {
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit('-----' . date('Y-m-d h:i:s', time()) . '-----');
		}
	}
	else {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		exit('-----' . date('Y-m-d h:i:s', time()) . '-----');
	}
}


?>
