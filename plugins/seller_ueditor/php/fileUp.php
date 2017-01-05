<?php
//dezend by  QQ:2172298892
require 'config.php';

if (!$enable) {
	exit('{"url":"","fileType":"","original":"","state":"没有上传权限"}');
}

$config = array(
	'savePath'   => $root_path_relative . DATA_DIR . '/upload/',
	'allowFiles' => array('.rar', '.doc', '.docx', '.zip', '.pdf', '.txt', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.mov', '.wmv', '.mp4', '.webm', '.flv'),
	'maxSize'    => 1024 * 50
	);
$up = new Uploader('upfile', $config);
$info = $up->getFileInfo();
$info['url'] = str_replace($root_path_relative, $root_path, $info['url']);
echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['originalName'] . '","state":"' . $info['state'] . '"}';

?>
