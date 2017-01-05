<?php
//dezend by  QQ:2172298892
namespace custom\site\controllers;

class MY_SendController extends \http\site\controllers\IndexController
{
	public function MY_Test()
	{
		$message = array('code' => '1234', 'product' => 'sitename');
		$res = send_sms('18801828888', 'sms_signin', $message);

		if ($res !== true) {
			exit($res);
		}

		$res = send_mail('xxx', 'wanglin@ecmoban.com', 'title', 'content');

		if ($res !== true) {
			exit($res);
		}
	}
}

?>
