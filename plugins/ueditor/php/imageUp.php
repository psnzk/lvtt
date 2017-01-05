<?php
//dezend by  QQ:2172298892
require 'config.php';

if (!$enable) {
	exit('{\'url\':\'\',\'title\':\'\',\'original\':\'\',\'state\':\'没有上传权限\'}');
}

$config = array(
	'savePath'   => $root_path_relative . IMAGE_DIR . '/upload/',
	'maxSize'    => 3000,
	'allowFiles' => array('.gif', '.png', '.jpg', '.jpeg', '.bmp')
	);
$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);

if (isset($_GET['fetch'])) {
	header('Content-Type: text/javascript');
	echo 'updateSavePath(["upload"]);';
	return NULL;
}

$up = new Uploader('upfile', $config);
$info = $up->getFileInfo();
$info['url'] = str_replace($root_path_relative, $root_path, $info['url']);

if ($GLOBALS['_CFG']['open_oss'] == 1) {
	if ($info['url']) {
		$dir_url = explode(IMAGE_DIR, $info['url']);

		if (count($dir_url) == 2) {
			$desc_image = IMAGE_DIR . $dir_url[1];
			$urlip = get_ip_url($GLOBALS['ecs']->get_domain(), 1);
			$url_site = $urlip . $dir_url[0];
		}
		else {
			$desc_image = IMAGE_DIR . $dir_url;
			$url_site = get_ip_url($GLOBALS['ecs']->get_domain());
		}

		$bucket_info = get_bucket_info();
		$url = $url_site . 'oss.php?act=upload';
		$Http = new Http();
		$post_data = array(
			'bucket'    => $bucket_info['bucket'],
			'keyid'     => $bucket_info['keyid'],
			'keysecret' => $bucket_info['keysecret'],
			'is_cname'  => $bucket_info['is_cname'],
			'endpoint'  => $bucket_info['outside_site'],
			'object'    => array($desc_image)
			);
		$Http->doPost($url, $post_data);
	}
}

echo '{\'url\':\'' . $info['url'] . '\',\'title\':\'' . $title . '\',\'original\':\'' . $info['originalName'] . '\',\'state\':\'' . $info['state'] . '\'}';

?>
