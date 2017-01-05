<?php
//dezend by  QQ:2172298892
defined('IN_ECTOUCH') || exit('Deny Access');
$db_config = (file_exists(ROOT_PATH . 'config/database.php') ? require ROOT_PATH . 'config/database.php' : array());
return array(
	'ENV'                => ENVIRONMENT,
	'TIMEZONE'           => $timezone,
	'DEFAULT_APP'        => 'site',
	'DEFAULT_CONTROLLER' => 'Index',
	'ACTION_PREFIX'      => 'action',
	'DEFAULT_FILTER'     => 'htmlspecialchars',
	'VAR_FILTER'         => '',
	'CACHE_EXPIRE'       => 86400,
	'DB'                 => array('default' => $db_config),
	'COOKIE_EXPIRE'      => 3600,
	'COOKIE_DOMAIN'      => '',
	'COOKIE_PATH'        => '/',
	'COOKIE_PREFIX'      => '',
	'COOKIE_SECURE'      => false,
	'COOKIE_HTTPONLY'    => '',
	'CACHE'              => array(
		'default'   => array('CACHE_TYPE' => 'FileCache', 'CACHE_PATH' => CACHE_PATH . 'caches/', 'GROUP' => 'd', 'HASH_DEEP' => 0),
		'memcached' => array('CACHE_TYPE' => 'FileCache', 'CACHE_PATH' => CACHE_PATH . 'caches/', 'GROUP' => 'f', 'HASH_DEEP' => 0),
		'TPL_CACHE' => array('CACHE_TYPE' => 'FileCache', 'CACHE_PATH' => CACHE_PATH . 'caches/', 'GROUP' => 'f', 'HASH_DEEP' => 0),
		'DB_CACHE'  => array('CACHE_TYPE' => 'FileCache', 'CACHE_PATH' => CACHE_PATH . 'caches/', 'GROUP' => 'd', 'HASH_DEEP' => 0)
		)
	);

?>
