<?php
//dezend by  QQ:2172298892
define('ROOT_PATH', str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))) . '/');
define('APP_NAME', 'respond');
$_GET['code'] = 'alipay';
$_GET['type'] = 'notify';
require ROOT_PATH . 'index.php';

?>
