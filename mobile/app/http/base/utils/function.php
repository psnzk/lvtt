<?php
//dezend by  QQ:2172298892
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


?>
