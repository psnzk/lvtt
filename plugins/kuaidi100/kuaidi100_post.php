<?php
//dezend by  QQ:2172298892
$getcom = $_GET['com'];
$getNu = $_GET['nu'];
include_once 'kuaidi100_config.php';
if (isset($postcom) && isset($getNu)) {
	$url = 'http://www.kuaidi100.com/applyurl?key=' . $kuaidi100key . '&com=' . $postcom . '&nu=' . $getNu;
	$powered = '查询服务由：<a href="http://www.kuaidi100.com" target="_blank" style="color:blue">快递100</a> 网站提供';

	if (function_exists('curl_init') == 1) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		$get_content = curl_exec($curl);
		curl_close($curl);
	}
	else {
		include 'snoopy.php';
		$snoopy = new snoopy();
		$snoopy->fetch($url);
		$get_content = $snoopy->results;
	}

	echo '<iframe src="' . $get_content . '" width="534" height="340" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="yes"><br/>' . $powered;
}
else {
	echo '查询失败，请重试';
}

exit();
echo "\r";

?>
